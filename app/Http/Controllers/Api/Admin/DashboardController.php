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
        // 1. Total Customers (Assuming role 'user' or default role)
        $totalCustomers = User::where('role', 'user')->count();

        // 2. Active Bookings (Pending, Accepted, Started, On the way)
        $activeBookings = Booking::whereNotIn('status', ['Completed', 'Cancelled', 'cancelled', 'completed'])->count();
        $activeCustomBookings = CustomBooking::whereNotIn('status', ['Completed', 'Cancelled', 'cancelled', 'completed'])->count();
        $totalActiveBookings = $activeBookings + $activeCustomBookings;

        // 3. Monthly Revenue
        $monthlyRevenue = Transaction::where('status', 'success')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // 4. Total Transactions
        $totalTransactions = Transaction::where('status', 'success')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_customers' => $totalCustomers,
                'active_bookings' => $totalActiveBookings,
                'monthly_revenue' => $monthlyRevenue,
                'total_transactions' => $totalTransactions
            ]
        ]);
    }
}
