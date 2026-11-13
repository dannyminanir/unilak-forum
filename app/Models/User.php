<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'department',
        'year_of_study',
        'profile_photo',
        'bio',
        'role',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }

    // ── Role helpers ───────────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function isModeratorOrAdmin(): bool
    {
        return in_array($this->role, ['moderator', 'admin']);
    }

    public function canAccessDepartment(?string $department): bool
    {
        if ($this->role !== 'student') {
            return true;
        }

        if (blank($department)) {
            return blank($this->department);
        }

        if (blank($this->department)) {
            return false;
        }

        return self::normalizeDepartmentValue($this->department) === self::normalizeDepartmentValue($department);
    }

    public function canAccessCategory(?Category $category): bool
    {
        if ($this->role !== 'student' || ! $category) {
            return true;
        }

        if ($category->type !== 'department') {
            return true;
        }

        return $this->canAccessDepartment($category->name);
    }

    public function canAccessPost(?Post $post): bool
    {
        if ($this->role !== 'student' || ! $post) {
            return true;
        }

        $author = $post->user;

        if (! $author) {
            return false;
        }

        if ($author->role !== 'student') {
            return true;
        }

        if (blank($this->department) || blank($author->department)) {
            return false;
        }

        return self::normalizeDepartmentValue($this->department) === self::normalizeDepartmentValue($author->department);
    }

    protected static function normalizeDepartmentValue(?string $department): string
    {
        $value = strtolower(trim((string) $department));

        return preg_replace('/^faculty\s+of\s+/i', '', $value);
    }

    // ── Forum relationships ────────────────────────────────────────────────
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'admin_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function savedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'saved_posts')
            ->withPivot('created_at');
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    /**
     * Check if this user has voted on a votable model.
     */
    public function hasVotedOn(Vote|Post|Reply $model): ?Vote
    {
        return $this->votes()
            ->where('votable_type', $model->getMorphClass())
            ->where('votable_id', $model->id)
            ->first();
    }

    /**
     * Check if a post is saved by this user.
     */
    public function hasSaved(Post $post): bool
    {
        return $this->savedPosts()->where('post_id', $post->id)->exists();
    }

    // ── Reputation & Badges ───────────────────────────────────────────────
    public function getReputationAttribute(): int
    {
        // Upvotes on posts
        $postUpvotes = (int) Vote::where('votable_type', Post::class)
            ->whereIn('votable_id', $this->posts()->pluck('id'))
            ->where('value', 1)
            ->count();

        // Upvotes on replies
        $replyUpvotes = (int) Vote::where('votable_type', Reply::class)
            ->whereIn('votable_id', $this->replies()->pluck('id'))
            ->where('value', 1)
            ->count();

        // Best answers authored
        $bestAnswers = (int) $this->replies()->where('is_best_answer', true)->count();

        return ($postUpvotes * 5) + ($replyUpvotes * 10) + ($bestAnswers * 25);
    }

    public function getReputationBadgeAttribute(): array
    {
        $rep = $this->reputation;

        if ($rep >= 500) {
            return ['title' => 'Academic Scholar', 'color' => 'bg-purple-100 text-purple-800 border-purple-200', 'icon' => '🏆'];
        } elseif ($rep >= 200) {
            return ['title' => 'Top Contributor', 'color' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => '⭐'];
        } elseif ($rep >= 50) {
            return ['title' => 'Active Student', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => '🌱'];
        }

        return ['title' => 'Freshman Member', 'color' => 'bg-gray-100 text-gray-700 border-gray-200', 'icon' => '🎓'];
    }

    // ── Profile photo URL ──────────────────────────────────────────────────
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/'.$this->profile_photo);
        }

        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=80";
    }
}
