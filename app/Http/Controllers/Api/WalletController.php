<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    private $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function index()
    {
        $user = Auth::user();
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00]
        );

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'wallet' => $wallet,
                'transactions' => $transactions
            ]
        ]);
    }

    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;
        
        $orderData = [
            'receipt'         => 'wallet_rcpt_' . Str::random(10),
            'amount'          => $amount * 100, // amount in the smallest currency unit
            'currency'        => 'INR'
        ];

        try {
            $razorpayOrder = $this->razorpay->order->create($orderData);
            
            return response()->json([
                'status' => 'success',
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'currency' => $orderData['currency'],
                'razorpay_key' => config('services.razorpay.key')
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
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'amount' => 'required|numeric'
        ]);

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            
            $user = Auth::user();
            
            // Add balance to wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0.00]
            );
            $wallet->balance += $request->amount;
            $wallet->save();

            // Create Transaction record
            Transaction::create([
                'user_id' => $user->id,
                'transaction_id' => $request->razorpay_payment_id,
                'amount' => $request->amount,
                'payment_mode' => 'online',
                'status' => 'completed',
                'type' => 'wallet_recharge',
                'description' => 'Added money to wallet'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Money added to wallet successfully',
                'balance' => $wallet->balance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 400);
        }
    }
}
