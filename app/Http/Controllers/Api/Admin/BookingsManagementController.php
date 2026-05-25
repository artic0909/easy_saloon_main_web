<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;
use App\Models\User;
use App\Notifications\BookingStatusNotification;

class BookingsManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'staff', 'items'])->where('status', 'completed');
        $customQuery = CustomBooking::with(['user', 'staff'])->where('status', 'completed');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  })
                  ->orWhereHas('staff', function($sq) use ($search) {
                      $sq->where('name', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  })
                  ->orWhereHas('staff', function($sq) use ($search) {
                      $sq->where('name', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }
        
        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();
        
        // Merge and sort
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at')->values();
        
        return response()->json([
            'status' => 'success',
            'data' => $allBookings
        ]);
    }

    public function pending(Request $request)
    {
        $query = Booking::with(['user', 'staff', 'items'])->whereNotIn('status', ['completed', 'cancelled']);
        $customQuery = CustomBooking::with(['user', 'staff'])->whereNotIn('status', ['completed', 'cancelled']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  })
                  ->orWhereHas('staff', function($sq) use ($search) {
                      $sq->where('name', 'like', "%$search%");
                  });
            });
            
            $customQuery->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  })
                  ->orWhereHas('staff', function($sq) use ($search) {
                      $sq->where('name', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
            $customQuery->whereDate('booking_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $customQuery->where('status', $request->status);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();
        
        // Merge and sort
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at')->values();
        
        return response()->json([
            'status' => 'success',
            'data' => $allBookings
        ]);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'staff', 'items'])->findOrFail($id);
        $staffMembers = User::where('role', 'staff')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'booking' => $booking,
                'staffMembers' => $staffMembers
            ]
        ]);
    }

    public function assignStaff(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $otp = $booking->otp ?? rand(1000, 9999);

        $booking->update([
            'staff_id' => $request->staff_id,
            'status' => 'confirmed',
            'otp' => $otp
        ]);

        $booking->user->notify(new BookingStatusNotification($booking, 'assigned'));

        return response()->json([
            'status' => 'success',
            'message' => 'Staff assigned and booking confirmed.',
            'data' => $booking
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,confirmed,accepted,on_the_way,started,completed,cancelled',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->status === 'confirmed' && !$booking->otp) {
            $updateData['otp'] = rand(1000, 9999);
        }

        $booking->update($updateData);

        $booking->user->notify(new BookingStatusNotification($booking, $request->status));

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated successfully.',
            'data' => $booking
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Booking deleted successfully.'
        ]);
    }

    // Custom Booking Methods
    public function customShow($id)
    {
        $booking = CustomBooking::with(['user', 'staff', 'address.city', 'address.state'])->findOrFail($id);
        $staffMembers = User::where('role', 'staff')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'booking' => $booking,
                'staffMembers' => $staffMembers
            ]
        ]);
    }

    public function customAssignStaff(Request $request, $id)
    {
        $booking = CustomBooking::findOrFail($id);
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $otp = $booking->otp ?? rand(1000, 9999);

        $booking->update([
            'staff_id' => $request->staff_id,
            'status' => 'confirmed',
            'otp' => $otp
        ]);

        $booking->user->notify(new BookingStatusNotification($booking, 'assigned'));

        return response()->json([
            'status' => 'success',
            'message' => 'Staff assigned and custom booking confirmed.',
            'data' => $booking
        ]);
    }

    public function customUpdateStatus(Request $request, $id)
    {
        $booking = CustomBooking::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,confirmed,accepted,on_the_way,started,completed,cancelled',
        ]);

        $updateData = ['status' => $request->status];
        if ($request->status === 'confirmed' && !$booking->otp) {
            $updateData['otp'] = rand(1000, 9999);
        }

        $booking->update($updateData);

        $booking->user->notify(new BookingStatusNotification($booking, $request->status));

        return response()->json([
            'status' => 'success',
            'message' => 'Custom booking status updated successfully.',
            'data' => $booking
        ]);
    }

    public function customDestroy($id)
    {
        $booking = CustomBooking::findOrFail($id);
        $booking->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Custom booking deleted successfully.'
        ]);
    }
}
