<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Service;
use App\Models\PackageItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageManageApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['items.service']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('details', 'like', "%$search%");
            });
        }

        $perPage = $request->get('per_page', 'all');
        if ($perPage === 'all') {
            $packages = $query->latest()->get();
            return response()->json(['packages' => $packages]);
        } else {
            $packages = $query->latest()->paginate((int)$perPage);
            return response()->json($packages);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'services']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->input('is_active', true);

        $packageSlug = strtoupper(Str::slug($request->name));
        $index = 1;
        do {
            $uniqueId = 'PK-' . $packageSlug . '-' . str_pad($index, 2, '0', STR_PAD_LEFT);
            $index++;
        } while (Package::where('unique_id', $uniqueId)->exists());
        $data['unique_id'] = $uniqueId;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);

        foreach ($request->services as $serviceId) {
            PackageItem::create([
                'package_id' => $package->id,
                'service_id' => $serviceId
            ]);
        }

        $package->load(['items.service']);

        return response()->json([
            'message' => 'Package created successfully.',
            'package' => $package
        ], 201);
    }

    public function show(Package $package)
    {
        $package->load(['items.service']);
        return response()->json(['package' => $package]);
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'existing_image' => 'nullable|string',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'services', 'existing_image']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->input('is_active', $package->is_active);

        if (empty($package->unique_id)) {
            $packageSlug = strtoupper(Str::slug($request->name));
            $index = 1;
            do {
                $uniqueId = 'PK-' . $packageSlug . '-' . str_pad($index, 2, '0', STR_PAD_LEFT);
                $index++;
            } while (Package::where('unique_id', $uniqueId)->exists());
            $data['unique_id'] = $uniqueId;
        }

        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        } else {
            if (empty($request->existing_image) && $package->image) {
                Storage::disk('public')->delete($package->image);
                $data['image'] = null;
            }
        }

        $package->update($data);

        PackageItem::where('package_id', $package->id)->delete();
        foreach ($request->services as $serviceId) {
            PackageItem::create([
                'package_id' => $package->id,
                'service_id' => $serviceId
            ]);
        }

        $package->load(['items.service']);

        return response()->json([
            'message' => 'Package updated successfully.',
            'package' => $package
        ]);
    }

    public function destroy(Package $package)
    {
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return response()->json(['message' => 'Package deleted successfully.']);
    }
}
