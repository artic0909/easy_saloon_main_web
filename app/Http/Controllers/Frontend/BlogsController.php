<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog;

class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(12);
        return view('frontend.blogs.index', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        $relatedBlogs = Blog::where('id', '!=', $id)->latest()->take(3)->get();
        return view('frontend.blogs.show', compact('blog', 'relatedBlogs'));
    }
}
