<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Overview stats matching web logic
        $stats = [
            'total_bookings' => Booking::where('staff_id', $user->id)->count(),
            'pending_bookings' => Booking::where('staff_id', $user->id)->where('status', 'Pending')->count(),
            'completed_bookings' => Booking::where('staff_id', $user->id)->where('status', 'Completed')->count(),
            'todays_bookings' => Booking::where(function($q) use ($user) {
                    $q->where('staff_id', $user->id)->orWhereNull('staff_id');
                })
                ->whereDate('booking_date', Carbon::today())
                ->count(),
            'rating' => $user->staff_rating,
            'rating_count' => $user->staff_rating_count,
        ];

        // Today's schedule (Assigned to me OR Unassigned)
        $today_bookings = Booking::where(function($q) use ($user) {
                $q->where('staff_id', $user->id)->orWhereNull('staff_id');
            })
            ->whereDate('booking_date', Carbon::today())
            ->with(['user:id,name,phone', 'items.service:id,name,image'])
            ->orderBy('time_slot', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => $stats,
                'today_bookings' => $today_bookings
            ]
        ]);
    }
}
