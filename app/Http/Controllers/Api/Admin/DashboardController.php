<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\CustomBooking;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Matching logic from App\Http\Controllers\Admin\AdminController
        $totalCustomers = User::where('role', 'user')->count();
        $totalBookings = Booking::count();
        $monthlyRevenue = Booking::where('is_paid', true)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('payable_amount')
            + CustomBooking::where('is_paid', true)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('payable_amount');
        $totalTransactions = Transaction::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_customers' => $totalCustomers,
                'total_bookings' => $totalBookings,
                'monthly_revenue' => $monthlyRevenue,
                'total_transactions' => $totalTransactions
            ]
        ]);
    }
}
