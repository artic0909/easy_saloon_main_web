<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
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
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
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
            'status' => 'success',
            'message' => 'Profile updated successfully!',
            'data' => $user
        ]);
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())
            ->with(['city', 'state', 'country'])
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $addresses
        ]);
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
            'is_primary' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Get or Create location IDs
        $country = \App\Models\Country::firstOrCreate(['name' => $request->country]);
        $state = \App\Models\State::firstOrCreate(['name' => $request->state, 'country_id' => $country->id]);
        $city = \App\Models\City::firstOrCreate(['name' => $request->city, 'state_id' => $state->id, 'country_id' => $country->id]);

        $isPrimary = filter_var($request->input('is_primary'), FILTER_VALIDATE_BOOLEAN);

        $data = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'full_address' => $request->full_address,
            'landmark' => $request->landmark,
            'city_id' => $city->id,
            'state_id' => $state->id,
            'country_id' => $country->id,
            'is_primary' => $isPrimary
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

        // Refresh relations
        $address->load(['city', 'state', 'country']);

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $address
        ]);
    }

    public function deleteAddress($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        
        // Manually nullify address_id in bookings to avoid database constraint violations
        \Illuminate\Support\Facades\DB::table('bookings')
            ->where('address_id', $address->id)
            ->update(['address_id' => null]);

        \Illuminate\Support\Facades\DB::table('custom_bookings')
            ->where('address_id', $address->id)
            ->update(['address_id' => null]);

        $address->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully.'
        ]);
    }
}
