<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'views',
        'is_pinned',
        'is_closed',
        'replies_count',
        'votes_count',
        'last_activity_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_closed' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class)->oldest();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_posts')->withTimestamps();
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeLatestActivity($query)
    {
        return $query->orderByDesc('is_pinned')->orderByDesc('last_activity_at');
    }

    public function scopeVisibleTo(Builder $query, User $viewer): Builder
    {
        if ($viewer->role !== 'student') {
            return $query;
        }

        $normalizedDepartment = strtolower(trim(preg_replace(
            '/^faculty\s+of\s+/i',
            '',
            (string) $viewer->department
        ) ?? ''));

        return $query->whereHas('user', function (Builder $authorQuery) use ($normalizedDepartment) {
            $authorQuery->where(function (Builder $visibilityQuery) use ($normalizedDepartment) {
                $visibilityQuery->where('role', '!=', 'student');

                if ($normalizedDepartment !== '') {
                    $visibilityQuery->orWhereRaw(
                        "LOWER(TRIM(REPLACE(LOWER(department), 'faculty of ', ''))) = ?",
                        [$normalizedDepartment]
                    );
                }
            });
        });
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function syncReplyStats(): void
    {
        $this->update([
            'replies_count' => $this->replies()->count(),
            'last_activity_at' => now(),
        ]);
    }

    public function getVoteScoreAttribute(): int
    {
        return $this->votes()->sum('value');
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::withTrashed()->where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
