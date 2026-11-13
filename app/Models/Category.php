<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'icon',
        'color',
        'sort_order',
    ];

    // ── Relationships ──────────────────────────────────────────────────────
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public static function generateSlug(string $name): string
    {
        $slug  = Str::slug($name);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
