<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
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
}
