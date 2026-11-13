<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can pin/unpin the post.
     */
    public function pin(User $user, Post $post): bool
    {
        return $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can lock/close the post.
     */
    public function lock(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isModeratorOrAdmin();
    }
}

