<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoBanner;
use Illuminate\Support\Facades\Storage;

class PromoBannerController extends Controller
{
    public function index()
    {
        $promos = PromoBanner::latest()->get();
        return view('admin.cms.promo.index', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promo_banners', 'public');
        }

        PromoBanner::create($data);

        return back()->with('success', 'Promo Banner added successfully.');
    }

    public function update(Request $request, PromoBanner $promo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? true : false;

        if ($request->hasFile('image')) {
            if ($promo->image) {
                Storage::disk('public')->delete($promo->image);
            }
            $data['image'] = $request->file('image')->store('promo_banners', 'public');
        }

        $promo->update($data);

        return back()->with('success', 'Promo Banner updated successfully.');
    }

    public function destroy(PromoBanner $promo)
    {
        if ($promo->image) {
            Storage::disk('public')->delete($promo->image);
        }
        $promo->delete();
        return back()->with('success', 'Promo Banner deleted successfully.');
    }
}
