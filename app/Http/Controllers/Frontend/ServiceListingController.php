<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;


class ServiceListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category']);

        // Filter by Category
        if ($request->filled('category')) {
            $categorySlugs = is_array($request->category) ? $request->category : explode(',', $request->category);
            $query->whereHas('category', function($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }



        // Filter by Price
        if ($request->filled('max_price')) {
            $query->where('sale_price', '<=', $request->max_price);
        }

        // Search
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
                $query->latest(); // Default to newest/popular
                break;
        }

        $services = $query->paginate(12)->withQueryString();
        
        $categories = Category::where('is_active', true)->get();
        
        $selectedCategories = $request->filled('category') 
            ? (is_array($request->category) ? $request->category : explode(',', $request->category))
            : [];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.services.partials.service_list', compact('services'))->render(),
                'count' => $services->total(),
            ]);
        }

        return view('frontend.services.index', compact('services', 'categories'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->with(['category', 'equipment'])->firstOrFail();
        return view('frontend.services.show', compact('service'));
    }
}
