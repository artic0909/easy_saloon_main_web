<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Booking;
use App\Models\CustomBooking;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    /**
     * Create Razorpay Order
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'type' => 'required|in:regular,custom',
            'booking_id' => 'required|integer',
        ]);

        $type = $request->type;
        $bookingId = $request->booking_id;

        try {
            if ($type == 'regular') {
                $booking = Booking::findOrFail($bookingId);
                $amount = $booking->payable_amount;
            } else {
                $booking = CustomBooking::findOrFail($bookingId);
                $amount = $booking->total_price;
            }

            $orderData = [
                'receipt'         => $booking->booking_number,
                'amount'          => round($amount * 100), // Razorpay expects amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            $razorpayOrder = $this->api->order->create($orderData);
            
            // Store Razorpay Order ID
            $booking->update(['razorpay_order_id' => $razorpayOrder['id']]);

            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'key' => config('services.razorpay.key'),
                'booking_number' => $booking->booking_number,
                'user' => [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'contact' => auth()->user()->phone ?? '9999999999',
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify Razorpay Payment Signature
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'type' => 'required|in:regular,custom',
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        try {
            // Verify Signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);

            $type = $request->type;
            if ($type == 'regular') {
                $booking = Booking::where('razorpay_order_id', $request->razorpay_order_id)->first();
            } else {
                $booking = CustomBooking::where('razorpay_order_id', $request->razorpay_order_id)->first();
            }

            if ($booking) {
                $booking->update([
                    'is_paid' => true,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'status' => 'confirmed'
                ]);

                // Record Transaction
                Transaction::create([
                    'transaction_id' => $request->razorpay_payment_id,
                    'booking_id' => ($type == 'regular' ? $booking->id : null), // Transactions table might only link to regular bookings, checking...
                    'status' => 'success',
                    'payment_mode' => 'razorpay',
                    'amount' => $type == 'regular' ? $booking->payable_amount : $booking->total_price,
                ]);

                return response()->json(['success' => true, 'message' => 'Payment verified successfully']);
            }

            return response()->json(['success' => false, 'message' => 'Booking record not found'], 404);

        } catch (Exception $e) {
            Log::error('Razorpay Verification Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
