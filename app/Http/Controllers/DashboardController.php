<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Post;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'discussions' => Post::visibleTo($user)->count(),
            'announcements' => Announcement::published()->count(),
            'resources' => Resource::count(),
        ];

        $recentPosts = Post::visibleTo($user)
            ->with(['category', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $pinnedAnnouncements = Announcement::published()
            ->pinned()
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'pinnedAnnouncements' => $pinnedAnnouncements,
        ]);
    }
}
