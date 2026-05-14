<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->get()
            ->map(function ($category) {
                if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                    $category->image = asset('storage/' . $category->image);
                }
                return $category;
            });

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
