<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffLocation;
use App\Models\Booking;

class TrackingController extends Controller
{
    public function index()
    {
        // Get active bookings that are home-service and currently in progress
        $activeBookings = Booking::with(['staff.user', 'address'])
            ->where('service_type', 'home')
            ->whereIn('status', ['accepted', 'on_the_way', 'started'])
            ->get();

        // Get the latest location for each assigned staff
        $staffLocations = StaffLocation::with('staff.user')
            ->whereIn('booking_id', $activeBookings->pluck('id'))
            ->latest()
            ->get()
            ->unique('staff_id');

        return view('admin.tracking.index', compact('activeBookings', 'staffLocations'));
    }
}
