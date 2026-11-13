<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    /**
     * List all posts for administrative moderation.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $categoryId = $request->query('category_id');
        $filter = $request->query('filter');

        $query = Post::with(['user', 'category'])->withCount('replies')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($filter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($filter === 'closed') {
            $query->where('is_closed', true);
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::ordered()->get();

        return view('admin.posts.index', [
            'posts'      => $posts,
            'categories' => $categories,
            'search'     => $search,
            'categoryId' => $categoryId,
            'filter'     => $filter,
        ]);
    }

    public function togglePin(Post $post): RedirectResponse
    {
        $post->update(['is_pinned' => ! $post->is_pinned]);
        return back()->with('status', $post->is_pinned ? 'Post pinned 📌' : 'Post unpinned.');
    }

    public function toggleLock(Post $post): RedirectResponse
    {
        $post->update(['is_closed' => ! $post->is_closed]);
        return back()->with('status', $post->is_closed ? 'Post closed and locked 🔒' : 'Post reopened 🔓');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return back()->with('status', 'Post removed by administration.');
    }
}

