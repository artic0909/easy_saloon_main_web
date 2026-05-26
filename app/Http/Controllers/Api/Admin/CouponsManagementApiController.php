<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponsManagementApiController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Coupon::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%");
            });
        }

        $coupons = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $coupons
        ]);
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
            'is_active' => 'nullable|boolean'
        ]);

        $coupon = \App\Models\Coupon::create([
            'code' => $request->code,
            'title' => $request->title,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expiry_date' => $request->expiry_date,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ]);
    }

    public function show($id)
    {
        $coupon = \App\Models\Coupon::findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $coupon
        ]);
    }

    public function update(Request $request, $id)
    {
        $coupon = \App\Models\Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'title' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'is_active' => 'nullable|boolean'
        ]);

        $coupon->update([
            'code' => $request->code,
            'title' => $request->title,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expiry_date' => $request->expiry_date,
            'is_active' => $request->has('is_active') ? $request->is_active : $coupon->is_active,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon updated successfully',
            'data' => $coupon
        ]);
    }

    public function destroy($id)
    {
        $coupon = \App\Models\Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon deleted successfully'
        ]);
    }
}