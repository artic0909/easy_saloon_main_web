<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\Category;
use App\Models\SubCategory;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('is_active', true);

        // Filter by Category (ID or Slug)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by SubCategory (ID)
        if ($request->filled('subcategory_id')) {
            $query->where('sub_category_id', $request->subcategory_id);
        }

        // Filter by Max Price
        if ($request->filled('max_price')) {
            $query->where('sale_price', '<=', $request->max_price);
        }

        // Search by Name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort By
        switch ($request->get('sort')) {
            case 'price_low':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest();
                break;
        }

        $services = $query->get()->map(function ($service) {
            if ($service->image && !filter_var($service->image, FILTER_VALIDATE_URL)) {
                $service->image = asset('storage/' . $service->image);
            }
            return $service;
        });

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    public function byCategory($categoryId)
    {
        $services = Service::where('category_id', $categoryId)
            ->where('is_active', true)
            ->get()
            ->map(function ($service) {
                if ($service->image && !filter_var($service->image, FILTER_VALIDATE_URL)) {
                    $service->image = asset('storage/' . $service->image);
                }
                return $service;
            });

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    public function filters()
    {
        $categories = Category::where('is_active', true)->get();
        $subcategories = SubCategory::all();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'subcategories' => $subcategories,
                'max_price' => Service::max('sale_price') ?? 5000,
            ]
        ]);
    }
}
