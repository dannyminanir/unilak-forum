<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'is_best_answer',
        'votes_count',
    ];

    protected $casts = [
        'is_best_answer' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public function getVoteScoreAttribute(): int
    {
        return $this->votes()->sum('value');
    }

    /**
     * Mark this reply as the best answer (and unmark others in the same post).
     */
    public function markAsBestAnswer(): void
    {
        // Clear any existing best answer on this post
        static::where('post_id', $this->post_id)
            ->where('is_best_answer', true)
            ->update(['is_best_answer' => false]);

        $this->update(['is_best_answer' => true]);
    }
}

