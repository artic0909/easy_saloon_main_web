<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\CustomBooking;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomBookingController extends Controller
{
    public function checkout(Request $request)
    {
        $serviceIds = json_decode($request->service_ids, true) ?? [];
        if (empty($serviceIds)) {
            return redirect()->route('packages.custom')->with('error', 'Please select at least one service.');
        }

        $services = Service::whereIn('id', $serviceIds)->get();
        $totalPrice = 0;
        $totalDuration = 0;
        $originalPriceTotal = 0;
        
        $user = auth()->check() ? auth()->user() : null;
        $isFreeSecondBooking = $user ? $user->free_second_booking_available : false;
        $freeServiceIds = [];
        if ($isFreeSecondBooking) {
            $setting = \App\Models\SiteSetting::where('key', 'free_second_booking_services')->first();
            $freeServiceIds = $setting && $setting->value ? json_decode($setting->value, true) : [];
        }

        $freeApplied = false;

        foreach ($services as $service) {
            $price = $service->sale_price;
            $originalPriceTotal += $service->original_price;
            
            if ($isFreeSecondBooking && !$freeApplied && in_array($service->id, $freeServiceIds)) {
                $price = 0;
                $freeApplied = true;
                $service->is_free = true;
            } else {
                $service->is_free = false;
            }
            
            $totalPrice += $price;
            $totalDuration += $service->duration_minutes;
        }
        
        $type = $request->type ?? 'home';
        $date = $request->date;
        $slot = $request->slot;
        
        // Handle equipment flatten if needed
        $equipmentRaw = $request->equipments ? json_decode($request->equipments, true) : [];
        $equipment = [];
        if (is_array($equipmentRaw)) {
            foreach ($equipmentRaw as $val) {
                if (is_array($val)) {
                    foreach ($val as $v) $equipment[] = $v;
                } else {
                    $equipment[] = $val;
                }
            }
        }
        $equipment = array_unique($equipment);

        $userAddresses = [];
        $walletBalance = 0;
        if (auth()->check()) {
            $userAddresses = Address::where('user_id', auth()->id())->with(['city', 'state', 'country'])->get();
            $wallet = \App\Models\Wallet::firstOrCreate(['user_id' => auth()->id()]);
            $walletBalance = $wallet->balance;
        }

        return view('frontend.custom-checkout', compact('services', 'totalPrice', 'totalDuration', 'type', 'date', 'slot', 'userAddresses', 'equipment', 'serviceIds', 'walletBalance'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'service_ids' => 'required',
            'service_type' => 'required|in:home,salon',
            'date' => 'required|date',
            'slot' => 'required',
            'address_id' => 'required_if:service_type,home',
            'payment_method' => 'nullable|in:online,cash,wallet',
            'coupon_code' => 'nullable|string',
        ]);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.'], 401);
        }

        $serviceIds = json_decode($request->service_ids, true);
        $services = Service::whereIn('id', $serviceIds)->get();
        
        $totalPrice = 0;
        $totalDuration = 0;

        $user = auth()->user();
        $isFreeSecondBooking = $user->free_second_booking_available;
        $freeServiceIds = [];
        if ($isFreeSecondBooking) {
            $setting = \App\Models\SiteSetting::where('key', 'free_second_booking_services')->first();
            $freeServiceIds = $setting && $setting->value ? json_decode($setting->value, true) : [];
        }

        $freeApplied = false;

        foreach ($services as $service) {
            $price = $service->sale_price;
            if ($isFreeSecondBooking && !$freeApplied && in_array($service->id, $freeServiceIds)) {
                $price = 0;
                $freeApplied = true;
            }
            $totalPrice += $price;
            $totalDuration += $service->duration_minutes;
        }

        if ($freeApplied) {
            $user->update(['free_second_booking_available' => false]);
        }

        $booking = new CustomBooking();
        $booking->user_id = auth()->id();
        $booking->booking_number = 'CBK-' . strtoupper(Str::random(8));
        $booking->service_ids = $serviceIds;
        $booking->equipment = json_decode($request->equipment, true);
        $booking->total_price = $totalPrice;
        $booking->total_duration = $totalDuration;
        $booking->booking_date = $request->date;
        $booking->time_slot = $request->slot;
        $booking->service_type = $request->service_type;
        $booking->status = 'pending';

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
            if ($coupon && $totalPrice >= $coupon->min_order_amount) {
                if ($coupon->discount_type === 'percentage') {
                    $discountAmount = ($totalPrice * $coupon->discount_value) / 100;
                    if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                        $discountAmount = $coupon->max_discount;
                    }
                } else {
                    $discountAmount = $coupon->discount_value;
                }
            }
        }

        $booking->discount_amount = $discountAmount;
        $booking->payable_amount = max(0, $totalPrice - $discountAmount);
        
        if ($booking->payable_amount == 0) {
            $booking->is_paid = true;
            $booking->status = 'confirmed';
            $booking->payment_type = 'online';
            $booking->pay_type = 'online';
        } else {
            if ($request->payment_method === 'wallet') {
                $wallet = \App\Models\Wallet::firstOrCreate(['user_id' => auth()->id()]);
                if ($wallet->balance < $booking->payable_amount) {
                    return response()->json(['success' => false, 'message' => 'Insufficient wallet balance.'], 400);
                }
                $wallet->balance -= $booking->payable_amount;
                $wallet->save();
                
                \App\Models\Transaction::create([
                    'user_id' => auth()->id(),
                    'wallet_id' => $wallet->id,
                    'type' => 'debit',
                    'amount' => $booking->payable_amount,
                    'description' => 'Payment for Custom Booking #' . $booking->booking_number,
                    'status' => 'completed'
                ]);

                $booking->is_paid = true;
                $booking->status = 'confirmed';
                $booking->payment_type = 'wallet';
                $booking->pay_type = 'wallet';
            } else {
                $booking->payment_type = $request->payment_method === 'cash' ? 'cod' : 'online';
                $booking->pay_type = $request->payment_method ?? 'online';
            }
        }
        
        $booking->coupon_code = $request->filled('coupon_code') ? $request->coupon_code : null;

        if ($request->service_type == 'home') {
            $booking->address_id = $request->address_id;
        }

        $booking->save();

        // Send Notification
        auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));

        // Milestone Reset Check for Free Bookings (if it's confirmed immediately)
        if ($booking->status === 'confirmed') {
            $totalConfirmedBookings = \App\Models\Booking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count() + 
                                      \App\Models\CustomBooking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count();
            if (($totalConfirmedBookings === 1 && auth()->user()->role === 'user') || ($totalConfirmedBookings > 0 && $totalConfirmedBookings % 10 == 0)) {
                if (auth()->user()->scratch_card_claimed) {
                    auth()->user()->update(['scratch_card_claimed' => false]);
                }
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Custom booking placed successfully!',
            'booking_id' => $booking->id,
            'booking_type' => 'custom',
            'is_free' => ($booking->payable_amount == 0)
        ]);
    }
}
