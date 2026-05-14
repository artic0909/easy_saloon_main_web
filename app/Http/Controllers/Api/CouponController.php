<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', Carbon::today());
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $coupons
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', Carbon::today());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired coupon code'
            ], 404);
        }

        if ($request->amount < $coupon->min_order_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Minimum order amount for this coupon is ' . $coupon->min_order_amount
            ], 422);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($request->amount * $coupon->discount_value) / 100;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            $discount = $coupon->discount_value;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon applied successfully',
            'data' => [
                'code' => $coupon->code,
                'discount_amount' => round($discount, 2),
                'final_amount' => round($request->amount - $discount, 2),
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value
            ]
        ]);
    }
}
