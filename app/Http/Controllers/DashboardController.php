<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Address;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentBookings = Booking::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('frontend.dashboard.index', compact('user', 'recentBookings'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('profile_photos', 'public');
            $data['photo'] = $path;
        }

        $user->update($data);

        return response()->json([
            'success' => true, 
            'message' => 'Profile updated successfully!',
            'user' => $user
        ]);
    }

    public function bookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['staff', 'salon'])
            ->latest()
            ->get();
            
        return view('frontend.dashboard.bookings', compact('bookings'));
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);
        
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['success' => false, 'message' => 'This booking cannot be cancelled.']);
        }

        $booking->update(['status' => 'cancelled']);
        
        return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())
            ->with(['city', 'state', 'country'])
            ->get();
            
        $cities = \App\Models\City::all();
        $states = \App\Models\State::all();
        $countries = \App\Models\Country::all();
            
        return view('frontend.dashboard.addresses', compact('addresses', 'cities', 'states', 'countries'));
    }

    public function saveAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'full_address' => 'required|string',
            'landmark' => 'nullable|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $address = Address::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'full_address' => $request->full_address,
            'landmark' => $request->landmark,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'is_primary' => $request->has('is_primary')
        ]);

        if ($address->is_primary) {
            Address::where('user_id', auth()->id())
                ->where('id', '!=', $address->id)
                ->update(['is_primary' => false]);
        }

        return response()->json(['success' => true, 'message' => 'Address saved successfully.']);
    }

    public function deleteAddress($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();
        
        return response()->json(['success' => true, 'message' => 'Address deleted successfully.']);
    }

    public function wallet()
    {
        $user = auth()->user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->get();
            
        return view('frontend.dashboard.wallet', compact('wallet', 'transactions'));
    }

    public function notifications()
    {
        // For now, we can use Laravel's built-in notifications or a simple table
        $notifications = auth()->user()->notifications;
        return view('frontend.dashboard.notifications', compact('notifications'));
    }
}
