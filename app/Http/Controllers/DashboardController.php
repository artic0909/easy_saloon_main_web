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
        
        $bookings = Booking::where('user_id', $user->id)->latest()->take(5)->get();
        $customBookings = \App\Models\CustomBooking::where('user_id', $user->id)->latest()->take(5)->get();
        
        $recentBookings = $bookings->concat($customBookings)->sortByDesc('created_at')->take(5);
            
        $totalBookingsCount = Booking::where('user_id', $user->id)->count() + \App\Models\CustomBooking::where('user_id', $user->id)->count();
        $show_scratch_card = ($totalBookingsCount === 1 && !$user->scratch_card_claimed);

        return view('frontend.dashboard.index', compact('user', 'recentBookings', 'show_scratch_card'));
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

    public function bookings(Request $request)
    {
        $query = Booking::where('user_id', auth()->id())
            ->with(['staff', 'salon', 'items.service', 'items.package', 'address.city', 'address.state']);

        $customQuery = \App\Models\CustomBooking::where('user_id', auth()->id())
            ->with(['address.city', 'address.state']);

        if ($request->filter == 'upcoming') {
            $query->whereIn('status', ['pending', 'confirmed', 'accepted', 'on_the_way', 'started']);
            $customQuery->whereIn('status', ['pending', 'confirmed', 'accepted', 'on_the_way', 'started']);
        } elseif ($request->filter == 'past') {
            $query->whereIn('status', ['completed', 'cancelled']);
            $customQuery->whereIn('status', ['completed', 'cancelled']);
        }

        $bookings = $query->latest()->get();
        $customBookings = $customQuery->latest()->get();

        // Merge and sort
        $bookings = $bookings->concat($customBookings)->sortByDesc('created_at');
        
        $totalBookingsCount = Booking::where('user_id', auth()->id())->count() + \App\Models\CustomBooking::where('user_id', auth()->id())->count();
        $show_scratch_card = ($totalBookingsCount === 1 && !auth()->user()->scratch_card_claimed);
            
        return view('frontend.dashboard.bookings', compact('bookings', 'show_scratch_card'));
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

    public function rateBooking(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'type' => 'required|string|in:booking,custom_booking'
        ]);

        $rating = $request->input('rating');
        $type = $request->input('type');

        if ($type === 'custom_booking') {
            $booking = \App\Models\CustomBooking::where('user_id', auth()->id())->findOrFail($id);
        } else {
            $booking = Booking::where('user_id', auth()->id())->findOrFail($id);
        }

        $booking->update(['rating' => $rating]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'rating' => $rating
        ]);
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())
            ->with(['city', 'state', 'country'])
            ->get();
            
        return view('frontend.dashboard.addresses', compact('addresses'));
    }

    public function saveAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'nullable|exists:addresses,id',
            'title' => 'required|string|max:255',
            'full_address' => 'required|string',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        // Get or Create location IDs
        $country = \App\Models\Country::firstOrCreate(['name' => $request->country]);
        $state = \App\Models\State::firstOrCreate(['name' => $request->state, 'country_id' => $country->id]);
        $city = \App\Models\City::firstOrCreate(['name' => $request->city, 'state_id' => $state->id, 'country_id' => $country->id]);

        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'full_address' => $request->full_address,
            'landmark' => $request->landmark,
            'city_id' => $city->id,
            'state_id' => $state->id,
            'country_id' => $country->id,
            'is_primary' => $request->has('is_primary')
        ];

        if ($request->address_id) {
            $address = Address::where('user_id', auth()->id())->findOrFail($request->address_id);
            $address->update($data);
            $message = 'Address updated successfully.';
        } else {
            $address = Address::create($data);
            $message = 'Address saved successfully.';
        }

        if ($address->is_primary) {
            Address::where('user_id', auth()->id())
                ->where('id', '!=', $address->id)
                ->update(['is_primary' => false]);
        }

        return response()->json(['success' => true, 'message' => $message]);
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
        $user = auth()->user();
        $notifications = $user->notifications;
        
        // Mark all as read when viewing the page
        $user->unreadNotifications->markAsRead();

        return view('frontend.dashboard.notifications', compact('notifications'));
    }

    public function claimScratchCard()
    {
        $user = auth()->user();

        if ($user->scratch_card_claimed) {
            return response()->json(['success' => false, 'message' => 'Scratch card already claimed']);
        }

        $totalBookingsCount = Booking::where('user_id', $user->id)->count() + \App\Models\CustomBooking::where('user_id', $user->id)->count();

        if ($totalBookingsCount !== 1) {
            return response()->json(['success' => false, 'message' => 'Not eligible for scratch card']);
        }

        $user->scratch_card_claimed = true;
        $user->free_second_booking_available = true;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Scratch card claimed successfully!']);
    }
}
