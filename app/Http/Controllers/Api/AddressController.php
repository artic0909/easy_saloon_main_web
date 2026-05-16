<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())
            ->with(['city', 'state', 'country'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $addresses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'full_address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'is_primary' => 'nullable|boolean',
        ]);

        $address = Address::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'full_address' => $request->full_address,
            'landmark' => $request->landmark,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'is_primary' => $request->is_primary ?? false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address added successfully',
            'data' => $address
        ]);
    }
}
