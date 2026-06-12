<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15|unique:users'
        ]);

        $otp = rand(100000, 999999);
        \Illuminate\Support\Facades\Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));

        $accountSid = env('TWILIO_ACCOUNT_SID', '');
        $authToken = env('TWILIO_AUTH_TOKEN', ''); // Must be set in .env
        $messagingServiceSid = env('TWILIO_MESSAGING_SERVICE_SID', '');

        $phoneForTwilio = $request->phone;
        if (!str_starts_with($phoneForTwilio, '+')) {
            $phoneForTwilio = '+91' . ltrim($phoneForTwilio, '0');
        }

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'To' => $phoneForTwilio,
                'MessagingServiceSid' => $messagingServiceSid,
                'Body' => 'Your Esy Saloon Signup OTP is ' . $otp
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send OTP: ' . $response->body()
        ], 500);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->phone);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            \Illuminate\Support\Facades\Cache::forget('otp_' . $request->phone);
            \Illuminate\Support\Facades\Cache::put('verified_otp_' . $request->phone, true, now()->addMinutes(15));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid or expired OTP'
        ], 400);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $isVerified = \Illuminate\Support\Facades\Cache::get('verified_otp_' . $request->phone);
        if (!$isVerified) {
            return response()->json([
                'status' => 'error',
                'message' => 'Phone number not verified. Please verify OTP first.'
            ], 400);
        }

        $email = $request->phone . '@easysaloon.com';

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role for new registrations
        ]);

        \Illuminate\Support\Facades\Cache::forget('verified_otp_' . $request->phone);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => strtolower($user->role),
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // This will be email or phone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        if ($user->is_active === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is deactivated'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => strtolower($user->role),
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        
        $bookingsCount = \App\Models\Booking::where('user_id', $user->id)->count();
        $customBookingsCount = \App\Models\CustomBooking::where('user_id', $user->id)->count();
        $totalBookings = $bookingsCount + $customBookingsCount;

        $show_scratch_card = ($totalBookings === 1 && !$user->scratch_card_claimed);

        $userData = $user->toArray();
        $userData['show_scratch_card'] = $show_scratch_card;

        return response()->json([
            'status' => 'success',
            'data' => $userData
        ]);
    }
}
