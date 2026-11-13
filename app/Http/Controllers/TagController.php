<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display discussions associated with a tag.
     */
    public function show(Tag $tag, Request $request): View
    {
        $posts = $tag->posts()
            ->visibleTo($request->user())
            ->with(['user', 'category', 'tags'])
            ->withCount('replies')
            ->latestActivity()
            ->paginate(15);

        return view('tags.show', [
            'tag' => $tag,
            'posts' => $posts,
        ]);
    }
}
