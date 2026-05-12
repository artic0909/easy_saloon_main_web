<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Service;
use App\Models\PackageItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with(['items.service'])->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('details', 'like', "%$search%");
            });
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $packages = $query->latest()->get();
        } else {
            $packages = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $services = Service::with('category')->where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'original_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($data);

        // Add services to package
        foreach ($request->services as $serviceId) {
            PackageItem::create([
                'package_id' => $package->id,
                'service_id' => $serviceId
            ]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $services = Service::with('category')->where('is_active', true)->orderBy('name')->get();
        $selectedServices = $package->items->pluck('service_id')->toArray();
        return view('admin.packages.edit', compact('package', 'services', 'selectedServices'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string',
            'original_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($data);

        // Sync services (Simple way: delete and recreate)
        PackageItem::where('package_id', $package->id)->delete();
        foreach ($request->services as $serviceId) {
            PackageItem::create([
                'package_id' => $package->id,
                'service_id' => $serviceId
            ]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }
}
