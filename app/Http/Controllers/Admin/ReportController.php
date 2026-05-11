<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'booking'])->latest()->paginate(15);
        
        $revenueStats = [
            'total' => Booking::where('status', 'completed')->sum('payable_amount'),
            'this_month' => Booking::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('payable_amount'),
            'today' => Booking::where('status', 'completed')
                ->whereDate('created_at', now()->today())
                ->sum('payable_amount'),
        ];

        return view('admin.reports.index', compact('transactions', 'revenueStats'));
    }
}
