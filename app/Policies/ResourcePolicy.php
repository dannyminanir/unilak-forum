<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    /**
     * Determine whether the user can view any resources.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the resource.
     */
    public function view(?User $user, Resource $resource): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create resources.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the resource.
     */
    public function update(User $user, Resource $resource): bool
    {
        return $user->id === $resource->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can delete the resource.
     */
    public function delete(User $user, Resource $resource): bool
    {
        return $user->id === $resource->user_id || $user->isModeratorOrAdmin();
    }

    /**
     * Determine whether the user can download the resource.
     */
    public function download(User $user, Resource $resource): bool
    {
        return true;
    }
}

