<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Models\Salon;
use Illuminate\Support\Facades\Hash;
use DB;

class StaffController extends Controller
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

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $staffMembers = $query->latest()->get();
        } else {
            $staffMembers = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.staff.index', compact('staffMembers'));
    }

    public function create()
    {
        $salons = Salon::all();
        return view('admin.staff.create', compact('salons'));
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
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'staff',
            'is_active' => true,
            'salon_id' => $request->salon_id,
            'designation' => $request->designation,
            'experience_years' => $request->experience_years,
            'bio' => $request->bio,
            'is_available' => true,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        $salons = Salon::all();
        return view('admin.staff.edit', compact('staff', 'salons'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone' => 'nullable|string',
            'designation' => 'required|string',
            'salon_id' => 'nullable|exists:salons,id',
            'experience_years' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'salon_id' => $request->salon_id,
            'designation' => $request->designation,
            'experience_years' => $request->experience_years,
            'bio' => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Staff information updated successfully.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed successfully.');
    }
}
