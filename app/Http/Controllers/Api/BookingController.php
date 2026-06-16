<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function storeServiceBooking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'type' => 'required|in:home,salon',
            'date' => 'required|date',
            'slot' => 'required|string',
            'equipment' => 'nullable|array',
            'address_id' => 'nullable|exists:addresses,id',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $service = Service::findOrFail($request->service_id);
        $totalPrice = $service->sale_price;
        $discountAmount = $this->calculateDiscount($request->coupon_code, $totalPrice);
        $payableAmount = max(0, $totalPrice - $discountAmount);

        $this->applyFreeSecondBookingDiscount(auth()->user(), [$service->id], $payableAmount, $discountAmount, $totalPrice);

        $wallet = null;
        if ($request->payment_method === 'wallet') {
            $wallet = Wallet::where('user_id', auth()->id())->first();
            if (!$wallet || $wallet->balance < $payableAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            $isPaid = false;
            $status = 'pending';
            $payType = $request->payment_method ?? 'online';
            $paymentType = $request->payment_method === 'cash' ? 'cod' : ($request->payment_method === 'wallet' ? 'wallet' : 'online');

            if ($payableAmount == 0) {
                $isPaid = true;
                $status = 'confirmed';
                $paymentType = 'online';
                $payType = 'online';
            } elseif ($request->payment_method === 'wallet') {
                $isPaid = true;
                $status = 'confirmed';
            }

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $service->sale_price,
                'discount_amount' => $discountAmount,
                'payable_amount' => $payableAmount,
                'status' => $status,
                'is_paid' => $isPaid,
                'payment_type' => $paymentType,
                'pay_type' => $payType,
                'coupon_code' => $request->filled('coupon_code') ? $request->coupon_code : null,
                'equipment' => $request->equipment,
                'address_id' => $request->type == 'home' ? $request->address_id : null,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'item_type' => 'service',
                'price' => $service->sale_price,
                'quantity' => 1,
            ]);

            if ($request->payment_method === 'wallet' && $wallet) {
                $wallet->decrement('balance', $payableAmount);
                Transaction::create([
                    'user_id' => auth()->id(),
                    'booking_id' => $booking->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'amount' => $payableAmount,
                    'payment_mode' => 'wallet',
                    'status' => 'completed',
                    'type' => 'booking',
                    'description' => 'Payment for Booking ' . $booking->booking_number
                ]);
            }

            DB::commit();

            // Send Notification
            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification if mail not configured
            }

            // Milestone Reset Check for Free Bookings (if it's confirmed immediately)
            $show_scratch_card = false;
            $totalConfirmedBookings = 0;
            if ($status === 'confirmed') {
                $totalConfirmedBookings = Booking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count() + 
                                          \App\Models\CustomBooking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count();
                if (($totalConfirmedBookings === 1 && auth()->user()->role === 'user') || ($totalConfirmedBookings > 0 && $totalConfirmedBookings % 10 == 0)) {
                    if (auth()->user()->scratch_card_claimed) {
                        auth()->user()->update(['scratch_card_claimed' => false]);
                    }
                    $show_scratch_card = true;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $booking->load('items.service'),
                'is_free' => ($payableAmount == 0),
                'show_scratch_card' => $show_scratch_card,
                'total_confirmed_bookings' => $totalConfirmedBookings
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePackageBooking(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'type' => 'required|in:home,salon',
            'date' => 'required|date',
            'slot' => 'required|string',
            'equipment' => 'nullable|array',
            'address_id' => 'nullable|exists:addresses,id',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $package = \App\Models\Package::with('items.service')->findOrFail($request->package_id);
        $discountAmount = $this->calculateDiscount($request->coupon_code, $package->sale_price);
        $payableAmount = max(0, $package->sale_price - $discountAmount);

        $wallet = null;
        if ($request->payment_method === 'wallet') {
            $wallet = Wallet::where('user_id', auth()->id())->first();
            if (!$wallet || $wallet->balance < $payableAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            $isPaid = false;
            $status = 'pending';
            $payType = $request->payment_method ?? 'online';
            $paymentType = $request->payment_method === 'cash' ? 'cod' : ($request->payment_method === 'wallet' ? 'wallet' : 'online');

            if ($payableAmount == 0) {
                $isPaid = true;
                $status = 'confirmed';
                $paymentType = 'online';
                $payType = 'online';
            } elseif ($request->payment_method === 'wallet') {
                $isPaid = true;
                $status = 'confirmed';
            }

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $package->sale_price,
                'discount_amount' => $discountAmount,
                'payable_amount' => $payableAmount,
                'status' => $status,
                'is_paid' => $isPaid,
                'payment_type' => $paymentType,
                'pay_type' => $payType,
                'coupon_code' => $request->filled('coupon_code') ? $request->coupon_code : null,
                'equipment' => $request->equipment,
                'address_id' => $request->type == 'home' ? $request->address_id : null,
            ]);

            // Record the package as a main item
            BookingItem::create([
                'booking_id' => $booking->id,
                'package_id' => $package->id,
                'item_type' => 'package',
                'price' => $package->sale_price,
                'quantity' => 1,
            ]);

            // Record individual services included (with 0 price to match web)
            foreach ($package->items as $pItem) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'service_id' => $pItem->service_id,
                    'item_type' => 'service',
                    'price' => 0.00,
                    'quantity' => 1,
                ]);
            }

            if ($request->payment_method === 'wallet' && $wallet) {
                $wallet->decrement('balance', $payableAmount);
                Transaction::create([
                    'user_id' => auth()->id(),
                    'booking_id' => $booking->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'amount' => $payableAmount,
                    'payment_mode' => 'wallet',
                    'status' => 'completed',
                    'type' => 'booking',
                    'description' => 'Payment for Booking ' . $booking->booking_number
                ]);
            }

            DB::commit();

            // Send Notification
            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification
            }

            // Milestone Reset Check for Free Bookings (if it's confirmed immediately)
            $show_scratch_card = false;
            $totalConfirmedBookings = 0;
            if ($status === 'confirmed') {
                $totalConfirmedBookings = Booking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count() + 
                                          \App\Models\CustomBooking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count();
                if (($totalConfirmedBookings === 1 && auth()->user()->role === 'user') || ($totalConfirmedBookings > 0 && $totalConfirmedBookings % 10 == 0)) {
                    if (auth()->user()->scratch_card_claimed) {
                        auth()->user()->update(['scratch_card_claimed' => false]);
                    }
                    $show_scratch_card = true;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Package booking created successfully',
                'data' => $booking->load('items'),
                'is_free' => ($payableAmount == 0),
                'show_scratch_card' => $show_scratch_card,
                'total_confirmed_bookings' => $totalConfirmedBookings
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeCustomPackageBooking(Request $request)
    {
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'type' => 'required|in:home,salon',
            'date' => 'required|date',
            'slot' => 'required|string',
            'equipment' => 'nullable|array',
            'address_id' => 'nullable|exists:addresses,id',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $services = Service::whereIn('id', $request->service_ids)->get();
        $totalPrice = $services->sum('sale_price');
        $totalDuration = $services->sum('duration_minutes');
        $discountAmount = $this->calculateDiscount($request->coupon_code, $totalPrice);
        $payableAmount = max(0, $totalPrice - $discountAmount);

        $servicePrices = [];
        foreach ($request->service_ids as $id) {
            $s = $services->firstWhere('id', $id);
            $servicePrices[] = $s ? $s->sale_price : 0;
        }
        $this->applyFreeSecondBookingDiscount(auth()->user(), $request->service_ids, $payableAmount, $discountAmount, $totalPrice, $servicePrices);

        $wallet = null;
        if ($request->payment_method === 'wallet') {
            $wallet = Wallet::where('user_id', auth()->id())->first();
            if (!$wallet || $wallet->balance < $payableAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance.'
                ], 400);
            }
        }

        try {
            DB::beginTransaction();

            $isPaid = false;
            $status = 'pending';
            $payType = $request->payment_method ?? 'online';
            $paymentType = $request->payment_method === 'cash' ? 'cod' : ($request->payment_method === 'wallet' ? 'wallet' : 'online');

            if ($payableAmount == 0) {
                $isPaid = true;
                $status = 'confirmed';
                $paymentType = 'online';
                $payType = 'online';
            } elseif ($request->payment_method === 'wallet') {
                $isPaid = true;
                $status = 'confirmed';
            }

            $booking = \App\Models\CustomBooking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'CBK-' . strtoupper(Str::random(8)),
                'service_ids' => $request->service_ids,
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type,
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'payable_amount' => $payableAmount,
                'total_duration' => $totalDuration,
                'status' => $status,
                'is_paid' => $isPaid,
                'payment_type' => $paymentType,
                'pay_type' => $payType,
                'coupon_code' => $request->filled('coupon_code') ? $request->coupon_code : null,
                'equipment' => $request->equipment,
                'address_id' => $request->type == 'home' ? $request->address_id : null,
            ]);

            if ($request->payment_method === 'wallet' && $wallet) {
                $wallet->decrement('balance', $payableAmount);
                Transaction::create([
                    'user_id' => auth()->id(),
                    'custom_booking_id' => $booking->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'amount' => $payableAmount,
                    'payment_mode' => 'wallet',
                    'status' => 'completed',
                    'type' => 'booking',
                    'description' => 'Payment for Custom Booking ' . $booking->booking_number
                ]);
            }

            DB::commit();

            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification
            }

            // Milestone Reset Check for Free Bookings (if it's confirmed immediately)
            $show_scratch_card = false;
            $totalConfirmedBookings = 0;
            if ($status === 'confirmed') {
                $totalConfirmedBookings = Booking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count() + 
                                          \App\Models\CustomBooking::where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->count();
                if (($totalConfirmedBookings === 1 && auth()->user()->role === 'user') || ($totalConfirmedBookings > 0 && $totalConfirmedBookings % 10 == 0)) {
                    if (auth()->user()->scratch_card_claimed) {
                        auth()->user()->update(['scratch_card_claimed' => false]);
                    }
                    $show_scratch_card = true;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Custom package booking created successfully',
                'data' => $booking,
                'booking_type' => 'custom',
                'is_free' => ($payableAmount == 0),
                'show_scratch_card' => $show_scratch_card,
                'total_confirmed_bookings' => $totalConfirmedBookings
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateDiscount($couponCode, $totalPrice)
    {
        if (empty($couponCode)) {
            return 0;
        }

        $coupon = \App\Models\Coupon::where('code', $couponCode)
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
            return round($discountAmount, 2);
        }

        return 0;
    }

    private function applyFreeSecondBookingDiscount($user, $serviceIds, &$payableAmount, &$discountAmount, $totalPrice, $servicePrices = [])
    {
        if (!$user || !$user->free_second_booking_available) {
            return false;
        }

        $setting = \App\Models\SiteSetting::where('key', 'free_second_booking_services')->first();
        if (!$setting || empty($setting->value)) {
            return false;
        }

        $freeServiceIds = json_decode($setting->value, true) ?? [];
        if (empty($freeServiceIds)) {
            return false;
        }

        $freeAmount = 0;
        
        if (empty($servicePrices)) {
            if (in_array($serviceIds[0], $freeServiceIds)) {
                $freeAmount = $totalPrice;
            }
        } else {
            foreach ($serviceIds as $index => $id) {
                if (in_array($id, $freeServiceIds)) {
                    $freeAmount += $servicePrices[$index] ?? 0;
                }
            }
        }

        if ($freeAmount > 0) {
            $discountAmount += $freeAmount;
            if ($discountAmount > $totalPrice) {
                $discountAmount = $totalPrice;
            }
            $payableAmount = max(0, $totalPrice - $discountAmount);

            $user->free_second_booking_available = false;
            $user->save();
            
            return true;
        }

        return false;
    }
}
