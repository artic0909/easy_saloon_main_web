<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of active packages.
     */
    public function index()
    {
        $packages = Package::with('items.service')
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($package) {
                if ($package->image && !filter_var($package->image, FILTER_VALIDATE_URL)) {
                    $package->image = asset('storage/' . $package->image);
                }
                
                $package->items->map(function ($item) {
                    if ($item->service && $item->service->image && !filter_var($item->service->image, FILTER_VALIDATE_URL)) {
                        $item->service->image = asset('storage/' . $item->service->image);
                    }
                    return $item;
                });
                
                return $package;
            });

        return response()->json([
            'status' => 'success',
            'data' => $packages
        ]);
    }

    /**
     * Display the specified package.
     */
    public function show($id)
    {
        $package = Package::with('items.service')
            ->where(function($query) use ($id) {
                if (is_numeric($id)) {
                    $query->where('id', $id);
                } else {
                    $query->where('slug', $id);
                }
            })
            ->first();

        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Package not found'
            ], 404);
        }

        if ($package->image && !filter_var($package->image, FILTER_VALIDATE_URL)) {
            $package->image = asset('storage/' . $package->image);
        }

        $package->items->map(function ($item) {
            if ($item->service && $item->service->image && !filter_var($item->service->image, FILTER_VALIDATE_URL)) {
                $item->service->image = asset('storage/' . $item->service->image);
            }
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $package
        ]);
    }

    /**
     * Get categories and services for custom package creation.
     */
    public function customPackageData()
    {
        $categories = Category::where('is_active', true)
            ->with(['services' => function($query) {
                $query->where('is_active', true)->with('subCategory.equipment');
            }])
            ->get()
            ->map(function ($category) {
                $category->services->map(function ($service) {
                    if ($service->image && !filter_var($service->image, FILTER_VALIDATE_URL)) {
                        $service->image = asset('storage/' . $service->image);
                    }
                    return $service;
                });
                return $category;
            });

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
