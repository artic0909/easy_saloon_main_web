<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Equipment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('details', 'like', "%$search%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $services = $query->latest()->get();
        } else {
            $services = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        $service->load(['category', 'equipment']);
        return view('admin.services.show', compact('service'));
    }

    public function create()
    {
        $categories = Category::all();
        $equipment = Equipment::all();
        return view('admin.services.create', compact('categories', 'equipment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'equipment' => 'nullable|array',
            'equipment.*' => 'exists:equipment,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'details' => 'nullable|string',
            'what_included' => 'nullable|array',
            'what_included.*' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'equipment', 'what_included']);
        $data['what_included'] = array_filter($request->what_included ?? []);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        if ($request->has('equipment')) {
            $service->equipment()->sync($request->equipment);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = Category::all();
        $equipment = Equipment::all();
        return view('admin.services.edit', compact('service', 'categories', 'equipment'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'equipment' => 'nullable|array',
            'equipment.*' => 'exists:equipment,id',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'details' => 'nullable|string',
            'what_included' => 'nullable|array',
            'what_included.*' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'equipment', 'what_included']);
        $data['what_included'] = array_filter($request->what_included ?? []);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        if ($request->has('equipment')) {
            $service->equipment()->sync($request->equipment);
        } else {
            $service->equipment()->sync([]);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }


}
