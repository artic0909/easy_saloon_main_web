<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Service;
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
        $discountAmount = $this->calculateDiscount($request->coupon_code, $service->sale_price);
        $payableAmount = max(0, $service->sale_price - $discountAmount);

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $service->sale_price,
                'discount_amount' => $discountAmount,
                'payable_amount' => $payableAmount,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => $request->payment_method === 'cash' ? 'cod' : 'online',
                'pay_type' => $request->payment_method ?? 'online',
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

            DB::commit();

            // Send Notification
            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification if mail not configured
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $booking->load('items.service')
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

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $package->sale_price,
                'discount_amount' => $discountAmount,
                'payable_amount' => $payableAmount,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => $request->payment_method === 'cash' ? 'cod' : 'online',
                'pay_type' => $request->payment_method ?? 'online',
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

            DB::commit();

            // Send Notification
            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Package booking created successfully',
                'data' => $booking->load('items')
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

        try {
            DB::beginTransaction();

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
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => $request->payment_method === 'cash' ? 'cod' : 'online',
                'pay_type' => $request->payment_method ?? 'online',
                'coupon_code' => $request->filled('coupon_code') ? $request->coupon_code : null,
                'equipment' => $request->equipment,
                'address_id' => $request->type == 'home' ? $request->address_id : null,
            ]);

            DB::commit();

            try {
                auth()->user()->notify(new \App\Notifications\BookingConfirmation($booking));
            } catch (\Exception $e) {
                // Silently fail notification
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Custom package booking created successfully',
                'data' => $booking,
                'booking_type' => 'custom'
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
}
