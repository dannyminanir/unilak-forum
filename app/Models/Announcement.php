<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'content',
        'department',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForDepartment($query, ?string $dept)
    {
        $normalizedDept = strtolower(trim((string) $dept));
        $normalizedDept = preg_replace('/^faculty\s+of\s+/i', '', $normalizedDept);

        return $query->where(function ($q) use ($dept, $normalizedDept) {
            $q->whereNull('department')
              ->orWhere('department', $dept)
              ->orWhereRaw("LOWER(REPLACE(LOWER(department), 'faculty of ', '')) = ?", [$normalizedDept]);
        });
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }
}

