<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Post;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public landing page or redirect authenticated students to dashboard.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'students'      => User::count(),
            'discussions'   => Post::count(),
            'resources'     => Resource::count(),
            'categories'    => Category::count(),
        ];

        $latestAnnouncements = Announcement::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredCategories = Category::where('type', 'department')
            ->withCount('posts')
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        $trendingPosts = Post::with(['user', 'category'])
            ->withCount('replies')
            ->orderByDesc('replies_count')
            ->take(5)
            ->get();

        return view('welcome', [
            'stats'               => $stats,
            'latestAnnouncements' => $latestAnnouncements,
            'featuredCategories'  => $featuredCategories,
            'trendingPosts'       => $trendingPosts,
        ]);
    }
}

