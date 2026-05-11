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

        return view('frontend.checkout', compact('item', 'itemType', 'type', 'date', 'slot', 'userAddresses'));
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
        
        if ($request->item_type == 'service') {
            $service = Service::findOrFail($request->item_id);
            $booking->total_price = $service->sale_price;
            $booking->payable_amount = $service->sale_price;
        } else {
            $package = Package::findOrFail($request->item_id);
            $booking->total_price = $package->sale_price;
            $booking->payable_amount = $package->sale_price;
        }

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

        return response()->json([
            'success' => true, 
            'message' => 'Booking placed successfully!',
            'redirect' => route('dashboard.bookings')
        ]);
    }
}
