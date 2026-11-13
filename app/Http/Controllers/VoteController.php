<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Vote on a Post (+1 or -1).
     */
    public function votePost(Request $request, Post $post): RedirectResponse
    {
        $value = $request->input('value', 1) > 0 ? 1 : -1;
        $this->handleVote($request->user()->id, $post, $value);

        return back();
    }

    /**
     * Vote on a Reply (+1 or -1).
     */
    public function voteReply(Request $request, Reply $reply): RedirectResponse
    {
        $value = $request->input('value', 1) > 0 ? 1 : -1;
        $this->handleVote($request->user()->id, $reply, $value);

        return back();
    }

    /**
     * Common vote handling logic with toggle & flip support.
     */
    protected function handleVote(int $userId, Model $votable, int $value): void
    {
        $existingVote = Vote::where('user_id', $userId)
            ->where('votable_type', $votable->getMorphClass())
            ->where('votable_id', $votable->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->value === $value) {
                // Clicking same button again removes vote
                $existingVote->delete();
            } else {
                // Flip vote
                $existingVote->update(['value' => $value]);
            }
        } else {
            // New vote
            Vote::create([
                'user_id'      => $userId,
                'votable_type' => $votable->getMorphClass(),
                'votable_id'   => $votable->id,
                'value'        => $value,
            ]);
        }

        // Synchronize denormalized votes_count
        $newScore = (int) $votable->votes()->sum('value');
        $votable->update(['votes_count' => $newScore]);
    }
}

