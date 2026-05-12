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

        $customQuery = \App\Models\CustomBooking::where(function($q) {
                $q->where('staff_id', auth()->id())->orWhereNull('staff_id');
            })
            ->with(['user', 'address']);

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
            
            $customQuery->where(function($q) use ($search) {
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
            $customQuery->whereDate('booking_date', $request->date);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $customQuery->where('status', $request->status);
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        
        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();
        
        $allBookings = $bookings->concat($customBookings)->sortByDesc('created_at');

        if ($perPage != 'all') {
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $items = $allBookings->forPage($currentPage, $perPage);
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator($items, $allBookings->count(), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
        } else {
            $bookings = $allBookings;
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
                    'status' => 'accepted'
                ]);
                return back()->with('success', 'Booking accepted successfully.');
            }
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,On the way,Started,Completed,Rejected'
        ]);

        $statusMap = [
            'Accepted' => 'accepted',
            'On the way' => 'on_the_way',
            'Started' => 'started',
            'Completed' => 'completed',
            'Rejected' => 'cancelled'
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $booking->update([
            'status' => $dbStatus
        ]);

        return back()->with('success', 'Booking status updated to ' . $request->status);
    }

    public function customShow($id)
    {
        $booking = \App\Models\CustomBooking::where(function($q) {
                $q->where('staff_id', auth()->id())->orWhereNull('staff_id');
            })
            ->with(['user', 'address'])
            ->findOrFail($id);

        return view('staff.bookings.custom_show', compact('booking'));
    }

    public function customUpdateStatus(Request $request, $id)
    {
        $booking = \App\Models\CustomBooking::findOrFail($id);
        
        if ($booking->staff_id !== auth()->id()) {
            if ($booking->staff_id === null && $request->status === 'Accepted') {
                $booking->update([
                    'staff_id' => auth()->id(),
                    'status' => 'accepted'
                ]);
                return back()->with('success', 'Custom booking accepted successfully.');
            }
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,On the way,Started,Completed,Rejected'
        ]);

        $statusMap = [
            'Accepted' => 'accepted',
            'On the way' => 'on_the_way',
            'Started' => 'started',
            'Completed' => 'completed',
            'Rejected' => 'cancelled'
        ];

        $dbStatus = $statusMap[$request->status] ?? $request->status;

        $booking->update(['status' => $dbStatus]);

        return back()->with('success', 'Custom booking status updated to ' . $request->status);
    }
}
