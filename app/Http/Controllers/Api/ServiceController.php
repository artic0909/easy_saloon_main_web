<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\Category;


class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category'])->where('is_active', true);

        // Filter by Category (Support IDs or Slugs/Array)
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : explode(',', $request->category);
            $query->whereHas('category', function($q) use ($categories) {
                if (is_numeric($categories[0])) {
                    $q->whereIn('id', $categories);
                } else {
                    $q->whereIn('slug', $categories);
                }
            });
        } elseif ($request->filled('category_id')) {
            $categoryIds = is_array($request->category_id) ? $request->category_id : explode(',', $request->category_id);
            $query->whereIn('category_id', $categoryIds);
        }


        // Filter by Price
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



    public function show($id)
    {
        $service = Service::with(['category'])
            ->where(function($query) use ($id) {
                if (is_numeric($id)) {
                    $query->where('id', $id);
                } else {
                    $query->where('slug', $id);
                }
            })
            ->first();

        if (!$service) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service not found'
            ], 404);
        }

        if ($service->image && !filter_var($service->image, FILTER_VALIDATE_URL)) {
            $service->image = asset('storage/' . $service->image);
        }

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    public function filters()
    {
        $categories = Category::where('is_active', true)->get();
        $maxPrice = Service::max('sale_price') ?? 5000;
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
                'max_price' => $maxPrice + 1000, // Highest price + 1000
            ]
        ]);
    }
}
