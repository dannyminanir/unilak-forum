<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\StorePostRequest;
use App\Http\Requests\Forum\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of forum discussions across all categories with search and filters.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $sort = $request->query('sort', 'latest');
        $selectedCategory = $request->query('category');
        $search = $request->query('q');

        $query = Post::with(['user', 'category', 'tags'])
            ->withCount('replies');

        $query->visibleTo($user);

        // Search query
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
            });
        }

        // Category filter
        if ($selectedCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $selectedCategory));
        }

        // Sort conditions
        if ($sort === 'unanswered') {
            $query->where('replies_count', 0)->latest();
        } elseif ($sort === 'votes') {
            $query->orderByDesc('votes_count')->latest();
        } elseif ($sort === 'popular') {
            $query->orderByDesc('views')->latest();
        } elseif ($sort === 'discussed') {
            $query->orderByDesc('replies_count')->latest();
        } else {
            $query->latestActivity();
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::ordered()->get();

        return view('posts.index', [
            'posts' => $posts,
            'categories' => $categories,
            'currentSort' => $sort,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(Request $request): View
    {
        $categories = Category::ordered()->get();
        $defaultCategoryId = $request->query('category_id');

        return view('posts.create', [
            'categories' => $categories,
            'defaultCategoryId' => $defaultCategoryId,
        ]);
    }

    /**
     * Store a newly created post.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'last_activity_at' => now(),
        ]);

        if ($request->filled('tags')) {
            $this->syncTags($post, $request->tags);
        }

        return redirect()->route('posts.show', $post)
            ->with('status', 'Your topic has been posted successfully!');
    }

    /**
     * Display the specified post and its replies.
     */
    public function show(Post $post, Request $request): View
    {
        $user = $request->user();

        if ($user?->role === 'student' && ! $user->canAccessPost($post)) {
            abort(403, 'You can only view discussions from your faculty.');
        }

        $post->incrementViews();
        $post->load(['user', 'category', 'tags']);

        $userVote = $user ? $user->hasVotedOn($post) : null;
        $isSaved = $user ? $user->hasSaved($post) : false;

        $replies = $post->replies()
            ->with(['user', 'votes'])
            ->orderByDesc('is_best_answer')
            ->orderByDesc('votes_count')
            ->oldest()
            ->paginate(20);

        return view('posts.show', [
            'post' => $post,
            'replies' => $replies,
            'userVote' => $userVote,
            'isSaved' => $isSaved,
        ]);
    }

    /**
     * Show the form for editing the post.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        $categories = Category::ordered()->get();
        $existingTags = $post->tags->pluck('name')->implode(', ');

        return view('posts.edit', [
            'post' => $post,
            'categories' => $categories,
            'existingTags' => $existingTags,
        ]);
    }

    /**
     * Update the specified post.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $this->syncTags($post, $request->tags ?? '');

        return redirect()->route('posts.show', $post)
            ->with('status', 'Post updated successfully!');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')
            ->with('status', 'Post has been removed.');
    }

    /**
     * Toggle pinned status (moderators and admins only).
     */
    public function togglePin(Post $post): RedirectResponse
    {
        Gate::authorize('pin', $post);

        $post->update(['is_pinned' => ! $post->is_pinned]);
        $msg = $post->is_pinned ? 'Discussion pinned to top 📌' : 'Discussion unpinned.';

        return back()->with('status', $msg);
    }

    /**
     * Toggle locked/closed status.
     */
    public function toggleLock(Post $post): RedirectResponse
    {
        Gate::authorize('lock', $post);

        $post->update(['is_closed' => ! $post->is_closed]);
        $msg = $post->is_closed ? 'Discussion locked and closed 🔒' : 'Discussion reopened for replies 🔓';

        return back()->with('status', $msg);
    }

    protected function syncTags(Post $post, string $tagString): void
    {
        $tagNames = collect(explode(',', $tagString))
            ->map(fn ($tag) => trim($tag))
            ->filter(fn ($tag) => ! empty($tag))
            ->unique();

        $tagIds = [];
        foreach ($tagNames as $name) {
            $tag = Tag::findOrCreateByName($name);
            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);
    }
}
