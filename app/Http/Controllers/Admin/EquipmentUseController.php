<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Equipment;

class EquipmentUseController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $equipment = $query->latest()->get();
        } else {
            $equipment = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.equipment_uses.index', compact('equipment'));
    }

    public function create()
    {
        return view('admin.equipment_uses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/equipments'), $imageName);
            $data['image'] = 'images/equipments/' . $imageName;
        }

        Equipment::create($data);
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment created successfully.');
    }

    public function edit(Equipment $equipment_use)
    {
        return view('admin.equipment_uses.edit', compact('equipment_use'));
    }

    public function update(Request $request, Equipment $equipment_use)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/equipments'), $imageName);
            $data['image'] = 'images/equipments/' . $imageName;
        }

        $equipment_use->update($data);
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment_use)
    {
        $equipment_use->delete();
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment deleted successfully.');
    }
}
