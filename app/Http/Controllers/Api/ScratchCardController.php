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

        $bookingsCount = Booking::where('user_id', $user->id)->count();
        $customBookingsCount = CustomBooking::where('user_id', $user->id)->count();
        $totalBookings = $bookingsCount + $customBookingsCount;

        if ($totalBookings !== 1) {
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
}
