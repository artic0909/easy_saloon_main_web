<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'booking', 'customBooking'])->latest();

        // Filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_mode') && $request->payment_mode != '') {
            $query->where('payment_mode', $request->payment_mode);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'LIKE', "%$search%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%");
                  });
            });
        }

        $transactions = $query->paginate(15);

        return view('admin.payments.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'booking.items.service', 'customBooking'])->findOrFail($id);
        return view('admin.payments.show', compact('transaction'));
    }
}
