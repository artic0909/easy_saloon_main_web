<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffManagementApiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'staff');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('designation', 'like', "%$search%");
            });
        }

        $staffMembers = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $staffMembers
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'nullable|string',
            'designation' => 'required|string',
            'salon_id' => 'nullable|exists:salons,id',
            'experience_years' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'staff',
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'is_available' => $request->has('is_available') ? $request->is_available : true,
            'salon_id' => $request->salon_id,
            'designation' => $request->designation,
            'experience_years' => $request->experience_years,
            'bio' => $request->bio,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member created successfully',
            'data' => $staff
        ]);
    }

    public function show($id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    public function update(Request $request, $id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string',
            'designation' => 'required|string',
            'salon_id' => 'nullable|exists:salons,id',
            'experience_years' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'salon_id' => $request->salon_id,
            'designation' => $request->designation,
            'experience_years' => $request->experience_years,
            'bio' => $request->bio,
            'is_active' => $request->has('is_active') ? $request->is_active : $staff->is_active,
            'is_available' => $request->has('is_available') ? $request->is_available : $staff->is_available,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member updated successfully',
            'data' => $staff
        ]);
    }

    public function destroy($id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);
        $staff->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member deleted successfully'
        ]);
    }
}
