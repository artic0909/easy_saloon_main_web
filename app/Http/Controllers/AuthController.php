<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $redirect = route('dashboard');
            if ($user->role === 'admin') {
                $redirect = route('admin.dashboard');
            } elseif ($user->role === 'staff') {
                $redirect = route('staff.dashboard');
            }

            if ($request->ajax()) {
                $intendedUrl = redirect()->intended($redirect)->getTargetUrl();
                return response()->json(['success' => true, 'message' => 'Login successful! Redirecting...', 'redirect' => $intendedUrl]);
            }
            return redirect()->intended($redirect);
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.']);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15|unique:users'
        ]);

        $otp = rand(100000, 999999);
        \Illuminate\Support\Facades\Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));

        $accountSid = config('services.twilio.sid', '');
        $authToken = config('services.twilio.auth_token', '');
        $fromNumber = config('services.twilio.from', '');

        $phoneForTwilio = $request->phone;
        if (!str_starts_with($phoneForTwilio, '+')) {
            $phoneForTwilio = '+91' . ltrim($phoneForTwilio, '0');
        }

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($accountSid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                'To' => $phoneForTwilio,
                'From' => $fromNumber,
                'Body' => 'Your Esy Saloon Signup OTP is ' . $otp
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please check your number.'
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
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
            return back()->withErrors($validator)->withInput();
        }

        $isVerified = \Illuminate\Support\Facades\Cache::get('verified_otp_' . $request->phone);
        if (!$isVerified) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Phone number not verified. Please verify OTP first.']);
            }
            return back()->withErrors(['phone' => 'Please verify OTP first.'])->withInput();
        }

        $email = $request->phone . '@easysaloon.com';

        $user = User::create([
            'name' => 'USER',
            'email' => $email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
        ]);

        \Illuminate\Support\Facades\Cache::forget('verified_otp_' . $request->phone);

        Auth::login($user);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Registration successful! Welcome.', 'redirect' => route('dashboard')]);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
