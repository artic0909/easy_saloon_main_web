<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\BookingStatusNotification;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'staff', 'items']);

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
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $bookings = $query->latest()->get();
        } else {
            $bookings = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $staffMembers = User::where('role', 'staff')->get();
        return view('admin.bookings.show', compact('booking', 'staffMembers'));
    }

    public function assignStaff(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $booking->update([
            'staff_id' => $request->staff_id,
            'status' => 'confirmed'
        ]);

        $booking->user->notify(new BookingStatusNotification($booking, 'assigned'));

        return back()->with('success', 'Staff assigned and booking confirmed.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,accepted,on_the_way,started,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        $booking->user->notify(new BookingStatusNotification($booking, $request->status));

        return back()->with('success', 'Booking status updated successfully.');
    }
}
