<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Package;
use DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'bookings' => Booking::count(),
            'revenue' => Booking::where('status', 'completed')->sum('payable_amount'),
            'services' => Service::count(),
        ];

        $recentBookings = Booking::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentBookings', 'recentUsers'));
    }
}
