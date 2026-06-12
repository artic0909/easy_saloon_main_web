<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Service;

class ScratchCardSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::where('key', 'free_second_booking_services')->first();
        $freeServiceIds = [];
        if ($setting && $setting->value) {
            $freeServiceIds = json_decode($setting->value, true);
        }

        $services = Service::all();

        return view('admin.settings.scratch_card', compact('services', 'freeServiceIds'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'services' => 'nullable|array',
            'services.*' => 'integer|exists:services,id'
        ]);

        $setting = SiteSetting::firstOrCreate(
            ['key' => 'free_second_booking_services'],
            ['value' => json_encode([])]
        );

        $setting->value = json_encode($request->services ?? []);
        $setting->save();

        return redirect()->back()->with('success', 'Scratch card free services updated successfully.');
    }
}
