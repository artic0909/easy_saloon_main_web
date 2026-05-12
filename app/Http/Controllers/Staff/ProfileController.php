<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $staff = $user->staffProfile;
        return view('staff.profile.index', compact('user', 'staff'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($user->staffProfile) {
            $user->staffProfile->update([
                'bio' => $request->bio,
                'experience_years' => $request->experience_years
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateAvailability(Request $request)
    {
        $user = auth()->user();
        if ($user->staffProfile) {
            $user->staffProfile->update([
                'is_available' => $request->has('is_available')
            ]);
        }

        return back()->with('success', 'Availability status updated.');
    }
}
