<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search across posts, resources, and fellow students/faculty.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = trim($request->query('q', ''));
        $tab = $request->query('tab', 'all');

        $posts = collect();
        $resources = collect();
        $users = collect();

        if (strlen($query) >= 2) {
            if (in_array($tab, ['all', 'posts'])) {
                $postsQuery = Post::with(['user', 'category'])
                    ->withCount('replies')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->visibleTo($user);

                $posts = $postsQuery->latest()->take(15)->get();
            }

            if (in_array($tab, ['all', 'resources'])) {
                $resourcesQuery = Resource::with(['user', 'category'])
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%");
                    });

                if ($user?->role === 'student' && $user->department) {
                    $resourcesQuery->whereHas('category', function ($categoryQuery) use ($user) {
                        $categoryQuery->where(function ($q) use ($user) {
                            $q->where('type', '!=', 'department')
                                ->orWhere('name', $user->department);
                        });
                    });
                }

                $resources = $resourcesQuery->latest()->take(15)->get();
            }

            if (in_array($tab, ['all', 'users'])) {
                $usersQuery = User::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('student_id', 'like', "%{$query}%")
                        ->orWhere('department', 'like', "%{$query}%");
                });

                if ($user?->role === 'student' && $user->department) {
                    $usersQuery->where('department', $user->department);
                }

                $users = $usersQuery->take(15)->get();
            }
        }

        return view('search.index', [
            'query' => $query,
            'tab' => $tab,
            'posts' => $posts,
            'resources' => $resources,
            'users' => $users,
        ]);
    }
}
