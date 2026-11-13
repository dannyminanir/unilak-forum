<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Report a Post.
     */
    public function reportPost(Request $request, Post $post): RedirectResponse
    {
        return $this->submitReport($request, $post);
    }

    /**
     * Report a Reply.
     */
    public function reportReply(Request $request, Reply $reply): RedirectResponse
    {
        return $this->submitReport($request, $reply);
    }

    /**
     * Common polymorphic report handler.
     */
    protected function submitReport(Request $request, Model $reportable): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $userId = $request->user()->id;

        // Prevent duplicate reports from same user
        $exists = Report::where('user_id', $userId)
            ->where('reportable_type', $reportable->getMorphClass())
            ->where('reportable_id', $reportable->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already submitted a report for this item.');
        }

        Report::create([
            'user_id'         => $userId,
            'reportable_type' => $reportable->getMorphClass(),
            'reportable_id'   => $reportable->id,
            'reason'          => $validated['reason'],
            'status'          => 'pending',
        ]);

        return back()->with('status', 'Report received. Our moderation team will review it. Thank you!');
    }
}

