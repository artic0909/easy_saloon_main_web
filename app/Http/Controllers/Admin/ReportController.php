<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'booking', 'customBooking'])->latest();

        // Filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(15);
        
        $revenueStats = [
            'total' => Transaction::where('status', 'success')->sum('amount'),
            'this_month' => Transaction::where('status', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'today' => Transaction::where('status', 'success')
                ->whereDate('created_at', now()->today())
                ->sum('amount'),
        ];

        return view('admin.reports.index', compact('transactions', 'revenueStats'));
    }
}
