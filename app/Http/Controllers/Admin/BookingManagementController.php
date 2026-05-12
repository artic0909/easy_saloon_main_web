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
        $customQuery = \App\Models\CustomBooking::with(['user', 'staff']);

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

        $perPage = $request->get('per_page', 10);
        
        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();
        
        // Merge and sort
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
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }

    // Custom Booking Methods
    public function customShow($id)
    {
        $booking = \App\Models\CustomBooking::with(['user', 'staff', 'address.city', 'address.state'])->findOrFail($id);
        $staffMembers = User::where('role', 'staff')->get();
        return view('admin.bookings.custom_show', compact('booking', 'staffMembers'));
    }

    public function customAssignStaff(Request $request, $id)
    {
        $booking = \App\Models\CustomBooking::findOrFail($id);
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $booking->update([
            'staff_id' => $request->staff_id,
            'status' => 'confirmed'
        ]);

        $booking->user->notify(new BookingStatusNotification($booking, 'assigned'));

        return back()->with('success', 'Staff assigned and custom booking confirmed.');
    }

    public function customUpdateStatus(Request $request, $id)
    {
        $booking = \App\Models\CustomBooking::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,confirmed,accepted,on_the_way,started,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        $booking->user->notify(new BookingStatusNotification($booking, $request->status));

        return back()->with('success', 'Custom booking status updated successfully.');
    }

    public function customDestroy($id)
    {
        $booking = \App\Models\CustomBooking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Custom booking deleted successfully.');
    }
}
