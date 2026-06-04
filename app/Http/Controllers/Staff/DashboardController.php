<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Overview stats (Only for assigned)
        $stats = [
            'total_bookings' => Booking::where('staff_id', $user->id)->count(),
            'pending_bookings' => Booking::where('staff_id', $user->id)->where('status', 'Pending')->count(),
            'completed_bookings' => Booking::where('staff_id', $user->id)->where('status', 'Completed')->count(),
            'today_bookings' => Booking::where(function($q) use ($user) {
                    $q->where('staff_id', $user->id)->orWhereNull('staff_id');
                })
                ->whereDate('booking_date', Carbon::today())
                ->count(),
            'rating' => $user->staff_rating,
            'rating_count' => $user->staff_rating_count,
        ];

        // Daily schedule (Assigned to me OR Unassigned)
        $today_bookings = Booking::where(function($q) use ($user) {
                $q->where('staff_id', $user->id)->orWhereNull('staff_id');
            })
            ->whereDate('booking_date', Carbon::today())
            ->with(['user', 'items.service'])
            ->orderBy('time_slot', 'asc')
            ->get();

        // Recent bookings (Assigned to me OR Unassigned)
        $recent_bookings = Booking::where(function($q) use ($user) {
                $q->where('staff_id', $user->id)->orWhereNull('staff_id');
            })
            ->with(['user', 'items.service'])
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact('stats', 'today_bookings', 'recent_bookings'));
    }
}
