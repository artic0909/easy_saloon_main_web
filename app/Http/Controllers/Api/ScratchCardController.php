<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CustomBooking;

class ScratchCardController extends Controller
{
    public function claim(Request $request)
    {
        $user = $request->user();

        if ($user->scratch_card_claimed) {
            return response()->json([
                'status' => false,
                'message' => 'Scratch card already claimed'
            ], 400);
        }

        if ($user->role !== 'user') {
            return response()->json([
                'status' => false,
                'message' => 'Only users are eligible for scratch card'
            ], 400);
        }

        $totalConfirmedBookings = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count() + 
                                  \App\Models\CustomBooking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count();

        if ($totalConfirmedBookings !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not eligible for scratch card'
            ], 400);
        }

        $user->scratch_card_claimed = true;
        
        $winnersCount = \App\Models\Transaction::where('type', 'reward')->count();
        $isWinner = false;
        $rewardAmount = 0;

        if ($winnersCount < 3 && rand(1, 10) <= 5) { // 50% chance up to 3 winners
            $firstBooking = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->orderBy('created_at', 'asc')->first();
            $firstCustomBooking = \App\Models\CustomBooking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->orderBy('created_at', 'asc')->first();
            
            $oldestBooking = null;
            if ($firstBooking && $firstCustomBooking) {
                $oldestBooking = $firstBooking->created_at < $firstCustomBooking->created_at ? $firstBooking : $firstCustomBooking;
            } else {
                $oldestBooking = $firstBooking ?? $firstCustomBooking;
            }

            if ($oldestBooking) {
                $rewardAmount = $oldestBooking->payable_amount;
                if ($rewardAmount > 0) {
                    $isWinner = true;
                    
                    $wallet = \App\Models\Wallet::firstOrCreate(['user_id' => $user->id]);
                    $wallet->balance += $rewardAmount;
                    $wallet->save();

                    \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'wallet_id' => $wallet->id,
                        'type' => 'reward',
                        'amount' => $rewardAmount,
                        'description' => 'Scratch Card Reward (1st Booking Amount)',
                        'status' => 'completed'
                    ]);
                }
            }
        }

        $user->save();

        if ($isWinner) {
            return response()->json([
                'status' => true,
                'success' => true, 
                'is_winner' => true,
                'reward_amount' => $rewardAmount,
                'message' => 'Congratulations! You won ₹' . number_format($rewardAmount, 2) . ' in your wallet!'
            ]);
        } else {
            return response()->json([
                'status' => true,
                'success' => true, 
                'is_winner' => false,
                'message' => 'Better luck next time!'
            ]);
        }
    }

    public function status(Request $request)
    {
        $user = $request->user();

        $totalConfirmedBookings = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count() + 
                                  \App\Models\CustomBooking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'completed'])->count();

        $show_scratch_card = false;
        if ($user->role === 'user' && $totalConfirmedBookings === 1 && !$user->scratch_card_claimed) {
            $show_scratch_card = true;
        }

        return response()->json([
            'success' => true,
            'show_scratch_card' => $show_scratch_card,
            'total_confirmed_bookings' => $totalConfirmedBookings
        ]);
    }
}
