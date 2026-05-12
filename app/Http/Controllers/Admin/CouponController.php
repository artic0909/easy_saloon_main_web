<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;
use App\Notifications\CouponNotification;
use Illuminate\Support\Facades\Notification;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%");
            });
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $coupons = $query->latest()->get();
        } else {
            $coupons = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $coupon = Coupon::create($request->all());

        // Notify all users about the new coupon
        $users = User::where('role', 'user')->get();
        Notification::send($users, new CouponNotification($coupon));

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully and users notified.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function notifyUsers(Coupon $coupon)
    {
        $users = User::where('role', 'user')->get();
        Notification::send($users, new CouponNotification($coupon));

        return back()->with('success', 'Users notified about this coupon.');
    }
}
