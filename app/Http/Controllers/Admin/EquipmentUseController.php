<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Equipment;
use App\Models\SubCategory;

class EquipmentUseController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('subCategory.category');

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
        $subcategories = SubCategory::all();
        return view('admin.equipment_uses.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255'
        ]);
        Equipment::create($request->all());
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment created successfully.');
    }

    public function edit(Equipment $equipment_use)
    {
        $subcategories = SubCategory::all();
        return view('admin.equipment_uses.edit', compact('equipment_use', 'subcategories'));
    }

    public function update(Request $request, Equipment $equipment_use)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255'
        ]);
        $equipment_use->update($request->all());
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment_use)
    {
        $equipment_use->delete();
        return redirect()->route('admin.equipment_uses.index')->with('success', 'Equipment deleted successfully.');
    }
}
