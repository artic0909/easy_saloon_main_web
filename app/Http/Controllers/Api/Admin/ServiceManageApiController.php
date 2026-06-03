<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceManageApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category', 'equipment']);

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

        $perPage = $request->get('per_page', 'all');
        if ($perPage === 'all') {
            $services = $query->latest()->get();
            return response()->json(['services' => $services]);
        } else {
            $services = $query->latest()->paginate((int)$perPage);
            return response()->json($services);
        }
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

        $data = $request->except(['images', 'equipment', 'what_included']);
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

        $service->load(['category', 'equipment']);

        return response()->json([
            'message' => 'Service created successfully.',
            'service' => $service
        ], 201);
    }

    public function show(Service $service)
    {
        $service->load(['category', 'equipment']);
        return response()->json(['service' => $service]);
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

        $data = $request->except(['images', 'equipment', 'what_included', 'existing_images']);
        $data['what_included'] = array_filter($request->what_included ?? []);
        $data['slug'] = Str::slug($request->name);

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

        $service->load(['category', 'equipment']);

        return response()->json([
            'message' => 'Service updated successfully.',
            'service' => $service
        ]);
    }

    public function destroy(Service $service)
    {
        if ($service->images) {
            foreach ($service->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully.']);
    }
}
