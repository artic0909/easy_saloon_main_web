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
        ]);

        $service = Service::findOrFail($request->service_id);

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'booking_date' => $request->date,
                'time_slot' => $request->slot,
                'service_type' => $request->type,
                'total_price' => $service->sale_price,
                'payable_amount' => $service->sale_price,
                'status' => 'pending',
                'is_paid' => false,
                'payment_type' => 'cod', // Default for now
                'equipment' => $request->equipment,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'item_type' => 'service',
                'price' => $service->sale_price,
                'quantity' => 1,
            ]);

            DB::commit();

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
}
