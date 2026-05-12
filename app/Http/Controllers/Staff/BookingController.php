<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where(function($q) {
                $q->where('staff_id', auth()->id())->orWhereNull('staff_id');
            })
            ->with(['user', 'address', 'items.service']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $bookings = $query->latest()->get();
        } else {
            $bookings = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the logged in staff OR is unassigned
        if ($booking->staff_id !== null && $booking->staff_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['user', 'address', 'items.service']);
        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if ($booking->staff_id !== auth()->id()) {
            // If it's unassigned and they are trying to accept it
            if ($booking->staff_id === null && $request->status === 'Accepted') {
                $booking->update([
                    'staff_id' => auth()->id(),
                    'status' => 'Accepted'
                ]);
                return back()->with('success', 'Booking accepted successfully.');
            }
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,On the way,Started,Completed,Rejected'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Booking status updated to ' . $request->status);
    }
}
