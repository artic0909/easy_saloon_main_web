<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
            'booking_type' => 'required|in:regular,custom',
        ]);

        if ($request->booking_type == 'regular') {
            $booking = Booking::findOrFail($request->booking_id);
        } else {
            $booking = CustomBooking::findOrFail($request->booking_id);
        }

        $orderData = [
            'receipt'         => $booking->booking_number,
            'amount'          => $booking->payable_amount * 100, // amount in the smallest currency unit
            'currency'        => 'INR'
        ];

        try {
            $razorpayOrder = $this->razorpay->order->create($orderData);
            
            $booking->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            return response()->json([
                'status' => 'success',
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'currency' => $orderData['currency']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Razorpay Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required',
            'booking_type' => 'required|in:regular,custom',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            
            if ($request->booking_type == 'regular') {
                $booking = Booking::findOrFail($request->booking_id);
            } else {
                $booking = CustomBooking::findOrFail($request->booking_id);
            }

            $booking->update([
                'is_paid' => true,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_type' => 'online',
                'status' => 'confirmed'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed'
            ], 400);
        }
    }
}
