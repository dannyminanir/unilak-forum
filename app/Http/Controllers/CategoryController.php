<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories grouped by type.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $categories = Category::withCount([
            'posts' => fn ($query) => $query->visibleTo($user),
            'resources',
        ])
            ->ordered()
            ->get()
            ->groupBy('type');

        $departmentCategories = $categories->get('department', collect());

        if ($user?->role === 'student' && $user->department) {
            $departmentCategories = $departmentCategories->filter(fn ($category) => $user->canAccessDepartment($category->name));
        }

        return view('categories.index', [
            'departmentCategories' => $departmentCategories,
            'courseCategories' => $categories->get('course', collect()),
            'interestCategories' => $categories->get('interest', collect()),
        ]);
    }

    /**
     * Display posts within a single category with sorting/filtering.
     */
    public function show(Category $category, Request $request): View
    {
        $user = $request->user();

        if ($user?->role === 'student' && $category->type === 'department' && ! $user->canAccessDepartment($category->name)) {
            abort(403, 'You can only access your faculty board.');
        }

        $sort = $request->query('sort', 'latest');

        $postsQuery = $category->posts()
            ->visibleTo($user)
            ->with(['user', 'tags'])
            ->withCount('replies');

        // Apply sorting tabs
        if ($sort === 'unanswered') {
            $postsQuery->where('replies_count', 0)->latest();
        } elseif ($sort === 'popular') {
            $postsQuery->orderByDesc('views')->latest();
        } elseif ($sort === 'discussed') {
            $postsQuery->orderByDesc('replies_count')->latest();
        } else {
            // Default: pinned first, then newest activity or created
            $postsQuery->orderByDesc('is_pinned')->latest();
        }

        $posts = $postsQuery->paginate(15)->withQueryString();

        return view('categories.show', [
            'category' => $category,
            'posts' => $posts,
            'currentSort' => $sort,
        ]);
    }
}
