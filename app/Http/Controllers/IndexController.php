<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Feedback;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Banner;
use App\Models\PromoBanner;

class IndexController extends Controller
{
    public function index()
    {
        $achievements = Achievement::latest()->get();
        $feedbacks = Feedback::latest()->get();
        $blogs = Blog::latest()->take(3)->get();
        $categories = Category::where('is_active', true)->latest()->get();
        $banners = Banner::where('is_active', true)->latest()->get();
        $promo_banners = PromoBanner::where('is_active', true)->latest()->take(2)->get();
        
        return view('frontend.index', compact('achievements', 'feedbacks', 'blogs', 'categories','banners', 'promo_banners'));
    }
}
