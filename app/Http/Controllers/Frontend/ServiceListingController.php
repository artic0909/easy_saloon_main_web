<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Category;
use App\Models\SubCategory;

class ServiceListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category', 'subCategory']);

        // Filter by Category
        if ($request->filled('category')) {
            $categorySlugs = is_array($request->category) ? $request->category : explode(',', $request->category);
            $query->whereHas('category', function($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }

        // Filter by SubCategory
        if ($request->filled('subcategory')) {
            $subSlugs = is_array($request->subcategory) ? $request->subcategory : explode(',', $request->subcategory);
            $query->whereHas('subCategory', function($q) use ($subSlugs) {
                $q->whereIn('slug', $subSlugs);
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
        
        $categories = Category::with('subCategories')->where('is_active', true)->get();
        
        // Get relevant subcategories if a category is selected
        $selectedCategories = $request->filled('category') 
            ? (is_array($request->category) ? $request->category : explode(',', $request->category))
            : [];
            
        $relevantSubCategories = collect();
        if (!empty($selectedCategories)) {
            $relevantSubCategories = SubCategory::whereHas('category', function($q) use ($selectedCategories) {
                $q->whereIn('slug', $selectedCategories);
            })->get();
        }

        return view('frontend.services.index', compact('services', 'categories', 'relevantSubCategories'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->with('category')->firstOrFail();
        return view('frontend.services.show', compact('service'));
    }
}
