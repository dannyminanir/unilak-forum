<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Report;
use App\Models\Resource;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the main administration overview.
     */
    public function index(): View
    {
        $metrics = [
            'users'            => User::count(),
            'students'         => User::where('role', 'student')->count(),
            'admins'           => User::where('role', 'admin')->count(),
            'banned_users'     => User::where('is_banned', true)->count(),
            'posts'            => Post::count(),
            'replies'          => Reply::count(),
            'resources'        => Resource::count(),
            'categories'       => Category::count(),
            'pending_reports'  => Report::where('status', 'pending')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
        ];

        $latestUsers = User::latest()->take(5)->get();
        $latestPosts = Post::with(['user', 'category'])->latest()->take(5)->get();
        $recentReports = Report::with(['user', 'reportable'])->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', [
            'metrics'       => $metrics,
            'latestUsers'   => $latestUsers,
            'latestPosts'   => $latestPosts,
            'recentReports' => $recentReports,
        ]);
    }
}

