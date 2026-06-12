<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function getScratchCardSettings()
    {
        $setting = SiteSetting::where('key', 'free_second_booking_services')->first();
        
        $serviceIds = [];
        if ($setting && $setting->value) {
            $serviceIds = json_decode($setting->value, true);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'free_second_booking_services' => $serviceIds
            ]
        ]);
    }

    public function updateScratchCardSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'free_second_booking_services' => 'required|array',
            'free_second_booking_services.*' => 'integer|exists:services,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $setting = SiteSetting::firstOrCreate(
            ['key' => 'free_second_booking_services'],
            ['value' => json_encode([])]
        );

        $setting->value = json_encode($request->free_second_booking_services);
        $setting->save();

        return response()->json([
            'status' => true,
            'message' => 'Settings updated successfully',
            'data' => [
                'free_second_booking_services' => $request->free_second_booking_services
            ]
        ]);
    }
}
