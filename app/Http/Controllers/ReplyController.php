<?php

namespace App\Http\Controllers;

use App\Http\Requests\Forum\StoreReplyRequest;
use App\Http\Requests\Forum\UpdateReplyRequest;
use App\Models\Post;
use App\Models\Reply;
use App\Notifications\NewReplyNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    /**
     * Store a newly created reply for a post.
     * Returns JSON for AJAX requests, redirect for regular form submissions.
     */
    public function store(StoreReplyRequest $request, Post $post): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($user?->role === 'student' && ! $user->canAccessPost($post)) {
            abort(403, 'You can only reply in discussions from your faculty.');
        }

        Gate::authorize('create', [Reply::class, $post]);

        $reply = $post->replies()->create([
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        // Sync replies count & last activity
        $post->syncReplyStats();

        // In-app notification for post author (if someone else replied)
        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new NewReplyNotification($reply));
        }

        // AJAX / fetch request → return JSON
        if ($request->expectsJson()) {
            $reply->load('user');
            $user = $reply->user;
            $badge = $user->reputation_badge;

            return response()->json([
                'id'              => $reply->id,
                'content'         => $reply->content,
                'created_at'      => $reply->created_at->diffForHumans(),
                'is_best_answer'  => false,
                'user'            => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'initial'    => strtoupper(substr($user->name, 0, 1)),
                    'url'        => route('profile.show', $user),
                    'reputation' => $user->reputation,
                    'badge'      => $badge,
                ],
                'vote_route'       => route('replies.vote', $reply),
                'delete_route'     => route('replies.destroy', $reply),
                'report_route'     => route('replies.report', $reply),
                'can_delete'       => $user->id === $reply->user_id || $user->isModeratorOrAdmin(),
                'can_mark_best'    => $user->id === $post->user_id || $user->isModeratorOrAdmin(),
                'best_answer_route'=> route('replies.best-answer', $reply),
                'replies_count'    => $post->fresh()->replies_count,
            ]);
        }

        // Normal form submission fallback
        return redirect()->route('posts.show', $post)
            ->with('status', 'Your reply has been posted.');
    }

    /**
     * Update an existing reply.
     */
    public function update(UpdateReplyRequest $request, Reply $reply): RedirectResponse
    {
        Gate::authorize('update', $reply);

        $reply->update([
            'content' => $request->content,
        ]);

        return redirect()->route('posts.show', $reply->post)
            ->with('status', 'Reply updated successfully.');
    }

    /**
     * Delete an existing reply.
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        Gate::authorize('delete', $reply);

        $post = $reply->post;
        $reply->delete();

        $post->syncReplyStats();

        return redirect()->route('posts.show', $post)
            ->with('status', 'Reply removed.');
    }

    /**
     * Toggle or set a reply as the best answer (solution).
     */
    public function markBestAnswer(Reply $reply): RedirectResponse
    {
        Gate::authorize('markBestAnswer', $reply);

        if ($reply->is_best_answer) {
            $reply->update(['is_best_answer' => false]);
            $msg = 'Answer unmarked as solution.';
        } else {
            $reply->markAsBestAnswer();
            $msg = 'Reply marked as the accepted solution! 🎓';
        }

        return redirect()->route('posts.show', $reply->post)
            ->with('status', $msg);
    }
}

