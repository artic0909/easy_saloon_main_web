<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Staff;

class BookingManagementController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'staff', 'items'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $staffMembers = Staff::with('user')->where('is_available', true)->get();
        return view('admin.bookings.show', compact('booking', 'staffMembers'));
    }

    public function assignStaff(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        $booking->update([
            'staff_id' => $request->staff_id,
            'status' => 'confirmed'
        ]);

        return back()->with('success', 'Staff assigned and booking confirmed.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,accepted,on_the_way,started,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Booking status updated successfully.');
    }
}
