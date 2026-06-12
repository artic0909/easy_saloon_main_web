<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;

class ScratchCardController extends Controller
{
    public function claim(Request $request)
    {
        $user = $request->user();

        if ($user->scratch_card_claimed) {
            return response()->json([
                'status' => false,
                'message' => 'Scratch card already claimed'
            ], 400);
        }

        $totalConfirmedBookings = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count() + 
                                  \App\Models\CustomBooking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count();

        if (!($totalConfirmedBookings > 0 && ($totalConfirmedBookings == 1 || $totalConfirmedBookings % 10 == 0))) {
            return response()->json([
                'status' => false,
                'message' => 'Not eligible for scratch card'
            ], 400);
        }

        $user->scratch_card_claimed = true;
        $user->free_second_booking_available = true;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Scratch card claimed successfully. You now have a free second booking for eligible services!',
            'data' => [
                'free_second_booking_available' => true
            ]
        ]);
    }

    public function status(Request $request)
    {
        $user = $request->user();

        $totalConfirmedBookings = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count() + 
                                  \App\Models\CustomBooking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count();

        $show_scratch_card = false;
        if ($totalConfirmedBookings > 0 && ($totalConfirmedBookings == 1 || $totalConfirmedBookings % 10 == 0) && !$user->scratch_card_claimed) {
            $show_scratch_card = true;
        }

        return response()->json([
            'success' => true,
            'show_scratch_card' => $show_scratch_card,
            'total_confirmed_bookings' => $totalConfirmedBookings
        ]);
    }
}
