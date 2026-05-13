<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Package;
use App\Models\Transaction;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'bookings' => Booking::count(),
            'total_revenue' => Transaction::where('status', 'success')->sum('amount'),
            'monthly_revenue' => Transaction::where('status', 'success')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('amount'),
            'services' => Service::count(),
            'transactions_count' => Transaction::count(),
        ];

        $recentBookings = Booking::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentBookings', 'recentTransactions'));
    }
}
