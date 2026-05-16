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
        ]);

        $service = Service::findOrFail($request->service_id);

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $service->sale_price,
                'payable_amount' => $service->sale_price,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => 'cod',
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
        ]);

        $package = \App\Models\Package::with('items.service')->findOrFail($request->package_id);

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type == 'salon' ? 'salon_visit' : 'home',
                'total_price' => $package->sale_price,
                'payable_amount' => $package->sale_price,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => 'cod',
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
        ]);

        $services = Service::whereIn('id', $request->service_ids)->get();
        $totalPrice = $services->sum('sale_price');
        $totalDuration = $services->sum('duration_minutes');

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
                'total_duration' => $totalDuration,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => 'cod',
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
}
