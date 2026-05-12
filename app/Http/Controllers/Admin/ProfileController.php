<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Achievement;
use App\Models\Feedback;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Achievements (Numbers)
    public function numbers()
    {
        $achievements = Achievement::latest()->get();
        return view('admin.profile.numbers', compact('achievements'));
    }

    public function storeNumber(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'svg_icon' => 'required|string',
            'value' => 'required|string|max:255',
        ]);

        Achievement::create($validated);
        return back()->with('success', 'Achievement added successfully.');
    }

    public function updateNumber(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'svg_icon' => 'required|string',
            'value' => 'required|string|max:255',
        ]);

        $achievement->update($validated);
        return back()->with('success', 'Achievement updated successfully.');
    }

    public function deleteNumber(Achievement $achievement)
    {
        $achievement->delete();
        return back()->with('success', 'Achievement deleted successfully.');
    }

    // Feedbacks
    public function feedbacks()
    {
        $feedbacks = Feedback::latest()->paginate(10);
        return view('admin.profile.feedbacks.index', compact('feedbacks'));
    }

    public function createFeedback()
    {
        return view('admin.profile.feedbacks.create');
    }

    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
        ]);

        Feedback::create($validated);
        return redirect()->route('admin.profile.feedbacks')->with('success', 'Feedback added successfully.');
    }

    public function editFeedback(Feedback $feedback)
    {
        return view('admin.profile.feedbacks.edit', compact('feedback'));
    }

    public function updateFeedback(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
        ]);

        $feedback->update($validated);
        return redirect()->route('admin.profile.feedbacks')->with('success', 'Feedback updated successfully.');
    }

    public function deleteFeedback(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Feedback deleted successfully.');
    }

    // Media Coverage (Blogs)
    public function mediaCoverage()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.profile.blogs.index', compact('blogs'));
    }

    public function createBlog()
    {
        return view('admin.profile.blogs.create');
    }

    public function storeMediaCoverage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($validated);
        return redirect()->route('admin.profile.media_coverage')->with('success', 'Media coverage added successfully.');
    }

    public function showBlog(Blog $blog)
    {
        return view('admin.profile.blogs.view', compact('blog'));
    }

    public function editBlog(Blog $blog)
    {
        return view('admin.profile.blogs.edit', compact('blog'));
    }

    public function updateMediaCoverage(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($validated);
        return redirect()->route('admin.profile.media_coverage')->with('success', 'Media coverage updated successfully.');
    }

    public function deleteMediaCoverage(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return back()->with('success', 'Media coverage deleted successfully.');
    }

    // Settings
    public function settings()
    {
        $user = auth()->user();
        return view('admin.profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
