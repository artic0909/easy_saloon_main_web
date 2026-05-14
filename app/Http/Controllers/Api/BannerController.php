<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($banner) {
                // Ensure image has a full URL
                if ($banner->image && !filter_var($banner->image, FILTER_VALIDATE_URL)) {
                    $banner->image = asset('storage/' . $banner->image);
                }
                return $banner;
            });

        return response()->json([
            'status' => 'success',
            'data' => $banners
        ]);
    }
}
