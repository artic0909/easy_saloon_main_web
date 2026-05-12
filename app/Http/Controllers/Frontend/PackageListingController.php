<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageListingController extends Controller
{
    public function index()
    {
        $packages = Package::with('items.service')->where('is_active', true)->latest()->get();
        return view('frontend.packages.index', compact('packages'));
    }

    public function show($slug)
    {
        $package = Package::where('slug', $slug)->with('items.service')->firstOrFail();
        return view('frontend.packages.show', compact('package'));
    }

    public function customPackage()
    {
        $categories = \App\Models\Category::where('is_active', true)
            ->with(['services' => function($query) {
                $query->where('is_active', true)->with('subCategory.equipment');
            }])
            ->get();
            
        return view('frontend.packages.custom-package', compact('categories'));
    }
}
