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

        return redirect()->route('admin.categories.index');
    }

    public function show(Service $service)
    {
        return redirect()->route('admin.categories.index');
    }

    public function create()
    {
        return redirect()->route('admin.categories.index');
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
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->except(['images', 'equipment', 'what_included', 'image']);
        $data['what_included'] = array_filter($request->what_included ?? []);
        $data['slug'] = Str::slug($request->name);

        $category = Category::find($request->category_id);
        $categorySlug = strtoupper(Str::slug($category->name));
        $index = Service::where('category_id', $category->id)->count() + 1;
        do {
            $uniqueId = 'SR-' . $categorySlug . '-' . str_pad($index, 2, '0', STR_PAD_LEFT);
            $index++;
        } while (Service::where('unique_id', $uniqueId)->exists());
        $data['unique_id'] = $uniqueId;

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $uploadedImages[] = $file->store('services', 'public');
            }
        }
        $data['images'] = $uploadedImages;

        $service = Service::create($data);

        if ($request->has('equipment')) {
            $service->equipment()->sync($request->equipment);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return redirect()->route('admin.categories.index');
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
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string'
        ]);

        $data = $request->except(['images', 'equipment', 'what_included', 'image', 'existing_images']);
        $data['what_included'] = array_filter($request->what_included ?? []);
        $data['slug'] = Str::slug($request->name);

        // Self-heal: Generate unique_id if pre-existing service does not have one
        if (empty($service->unique_id)) {
            $category = Category::find($request->category_id);
            $categorySlug = strtoupper(Str::slug($category->name));
            $index = Service::where('category_id', $category->id)->count() + 1;
            do {
                $uniqueId = 'SR-' . $categorySlug . '-' . str_pad($index, 2, '0', STR_PAD_LEFT);
                $index++;
            } while (Service::where('unique_id', $uniqueId)->exists());
            $data['unique_id'] = $uniqueId;
        }

        $uploadedImages = $request->existing_images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $uploadedImages[] = $file->store('services', 'public');
            }
        }

        if ($service->images) {
            foreach ($service->images as $oldImage) {
                if (!in_array($oldImage, $uploadedImages)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }
        
        $data['images'] = $uploadedImages;

        $service->update($data);

        if ($request->has('equipment')) {
            $service->equipment()->sync($request->equipment);
        } else {
            $service->equipment()->sync([]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->images) {
            foreach ($service->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $service->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Service deleted successfully.');
    }


}
