<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\Reply;
use App\Models\User;

class ReplyPolicy
{
    /**
     * Determine whether the user can create a reply.
     */
    public function create(User $user, Post $post): bool
    {
        return ! $post->is_closed;
    }

    /**
     * Determine whether the user can update the reply.
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->id === $reply->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can delete the reply.
     */
    public function delete(User $user, Reply $reply): bool
    {
        return $user->id === $reply->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can mark a reply as the best answer.
     * Allowed for: the original post author or a moderator/admin.
     */
    public function markBestAnswer(User $user, Reply $reply): bool
    {
        return $user->id === $reply->post->user_id || $user->isModeratorOrAdmin();
    }
}

