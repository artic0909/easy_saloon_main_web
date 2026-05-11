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
    public function index()
    {
        $staffMembers = Staff::with(['user', 'salon'])->latest()->paginate(10);
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

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'staff',
                'is_active' => true,
            ]);

            Staff::create([
                'user_id' => $user->id,
                'salon_id' => $request->salon_id,
                'designation' => $request->designation,
                'experience_years' => $request->experience_years,
                'bio' => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.staff.index')->with('success', 'Staff member added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to add staff: ' . $e->getMessage());
        }
    }

    public function edit(Staff $staff)
    {
        $salons = Salon::all();
        return view('admin.staff.edit', compact('staff', 'salons'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->user_id,
            'phone' => 'nullable|string',
            'designation' => 'required|string',
            'salon_id' => 'nullable|exists:salons,id',
            'experience_years' => 'required|numeric|min:0',
            'bio' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $staff->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if ($request->filled('password')) {
                $staff->user->update(['password' => Hash::make($request->password)]);
            }

            $staff->update([
                'salon_id' => $request->salon_id,
                'designation' => $request->designation,
                'experience_years' => $request->experience_years,
                'bio' => $request->bio,
            ]);

            DB::commit();
            return redirect()->route('admin.staff.index')->with('success', 'Staff information updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update staff: ' . $e->getMessage());
        }
    }

    public function destroy(Staff $staff)
    {
        $staff->user->delete(); // Cascades to staff table
        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed successfully.');
    }
}
