<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedPostController extends Controller
{
    /**
     * Display a listing of posts saved by the authenticated user.
     */
    public function index(Request $request): View
    {
        $posts = $request->user()
            ->savedPosts()
            ->visibleTo($request->user())
            ->with(['category', 'user', 'tags'])
            ->withCount('replies')
            ->orderByPivot('created_at', 'desc')
            ->paginate(15);

        return view('posts.saved', [
            'posts' => $posts,
        ]);
    }

    /**
     * Toggle bookmark on a post.
     */
    public function toggle(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();
        $isSaved = $user->hasSaved($post);

        if ($isSaved) {
            $user->savedPosts()->detach($post->id);
            $msg = 'Post removed from your bookmarks.';
        } else {
            $user->savedPosts()->attach($post->id);
            $msg = 'Post saved to your bookmarks! 🔖';
        }

        return back()->with('status', $msg);
    }
}
