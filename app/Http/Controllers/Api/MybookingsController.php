<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;

class MybookingsController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Get standard bookings
        $bookings = Booking::where('user_id', $userId)
            ->with(['staff', 'salon', 'items.service', 'items.package', 'address.city', 'address.state'])
            ->latest()
            ->get();

        // Get custom bookings
        $customBookings = CustomBooking::where('user_id', $userId)
            ->with(['address.city', 'address.state', 'staff'])
            ->latest()
            ->get();

        // Tag them
        foreach ($bookings as $booking) {
            $booking->booking_type = 'regular';
        }
        foreach ($customBookings as $booking) {
            $booking->booking_type = 'custom';
        }

        // Merge and sort
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at')->values();

        return response()->json([
            'status' => 'success',
            'data' => $allBookings
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $type = $request->input('booking_type', 'regular');

        if ($type === 'custom') {
            $booking = CustomBooking::where('user_id', auth()->id())->findOrFail($id);
        } else {
            $booking = Booking::where('user_id', auth()->id())->findOrFail($id);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'This booking cannot be cancelled.'
            ], 400);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking cancelled successfully.'
        ]);
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'booking_type' => 'required|string|in:regular,custom'
        ]);

        if ($request->booking_type === 'custom') {
            $booking = CustomBooking::where('user_id', auth()->id())->findOrFail($id);
        } else {
            $booking = Booking::where('user_id', auth()->id())->findOrFail($id);
        }

        $booking->update(['rating' => $request->rating]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating submitted successfully!',
            'rating' => $request->rating
        ]);
    }
}
