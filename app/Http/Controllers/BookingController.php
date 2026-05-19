<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function checkout(Request $request)
    {
        $type = $request->type ?? 'home';
        $date = $request->date;
        $slot = $request->slot;
        $equipment = $request->equipment ? json_decode($request->equipment, true) : [];
        
        $item = null;
        if ($request->has('service_id')) {
            $item = Service::findOrFail($request->service_id);
            $itemType = 'service';
        } elseif ($request->has('package_id')) {
            $item = Package::findOrFail($request->package_id);
            $itemType = 'package';
        } else {
            return redirect()->route('home')->with('error', 'Please select a service or package.');
        }

        $userAddresses = [];
        if (auth()->check()) {
            $userAddresses = Address::where('user_id', auth()->id())->with(['city', 'state', 'country'])->get();
        }

        return view('frontend.checkout', compact('item', 'itemType', 'type', 'date', 'slot', 'userAddresses', 'equipment'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'item_type' => 'required|in:service,package',
            'service_type' => 'required|in:home,salon',
            'date' => 'required|date',
            'slot' => 'required',
            'address_id' => 'required_if:service_type,home',
            'payment_method' => 'nullable|in:online,cash',
            'coupon_code' => 'nullable|string',
        ]);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.'], 401);
        }

        $booking = new Booking();
        $booking->user_id = auth()->id();
        $booking->booking_number = 'BK-' . strtoupper(Str::random(8));
        $booking->booking_date = $request->date;
        $booking->time_slot = $request->slot;
        $booking->service_type = $request->service_type == 'salon' ? 'salon_visit' : 'home';
        $booking->status = 'pending';
        
        // Handle equipment storage
        if ($request->has('equipment')) {
            $equipmentData = $request->equipment;
            if (is_string($equipmentData)) {
                $decoded = json_decode($equipmentData, true);
                $booking->equipment = is_array($decoded) ? $decoded : [$equipmentData];
            } else {
                $booking->equipment = $equipmentData;
            }
        }
        
        if ($request->item_type == 'service') {
            $service = Service::findOrFail($request->item_id);
            $booking->total_price = $service->sale_price;
        } else {
            $package = Package::findOrFail($request->item_id);
            $booking->total_price = $package->sale_price;
        }

        // Calculate coupon discount
        $discountAmount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', \Carbon\Carbon::today());
                })
                ->first();
            if ($coupon && $booking->total_price >= $coupon->min_order_amount) {
                if ($coupon->discount_type === 'percentage') {
                    $discountAmount = ($booking->total_price * $coupon->discount_value) / 100;
                    if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                        $discountAmount = $coupon->max_discount;
                    }
                } else {
                    $discountAmount = $coupon->discount_value;
                }
            }
        }

        $booking->discount_amount = $discountAmount;
        $booking->payable_amount = max(0, $booking->total_price - $discountAmount);
        $booking->payment_type = $request->payment_method === 'cash' ? 'cod' : 'online';
        $booking->pay_type = $request->payment_method ?? 'online';
        $booking->coupon_code = $request->filled('coupon_code') ? $request->coupon_code : null;

        if ($request->service_type == 'home') {
            $booking->address_id = $request->address_id;
        }

        $booking->save();

        // If it's a service, add to items
        if ($request->item_type == 'service') {
            $booking->items()->create([
                'service_id' => $request->item_id,
                'item_type' => 'service',
                'price' => $booking->total_price,
                'quantity' => 1
            ]);
        } else {
            // For packages, add the package itself and its items
            $package = Package::with('items')->findOrFail($request->item_id);
            
            // Record the package as a main item
            $booking->items()->create([
                'package_id' => $package->id,
                'item_type' => 'package',
                'price' => $booking->total_price,
                'quantity' => 1
            ]);

            // Optional: Also record individual services included (with 0 price)
            foreach ($package->items as $pItem) {
                $booking->items()->create([
                    'service_id' => $pItem->service_id,
                    'item_type' => 'service',
                    'price' => 0.00,
                    'quantity' => 1
                ]);
            }
        }

        // Send Notification
        auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));

        return response()->json([
            'success' => true, 
            'message' => 'Booking placed successfully!',
            'booking_id' => $booking->id,
            'booking_type' => 'regular'
        ]);
    }
}
