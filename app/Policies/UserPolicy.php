<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the given user is an admin.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->role === "Admin"; // Only admin
    }

    /**
     * Determine if the given user is an admin or if the user is the same as the given id.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $id
     * @return bool
     */
    public function admin_userId(User $user, User $id)
    {
        return $user->role === "Admin" || $user->id === $id->id; // Admin or the user itself
    }

    /**
     * Determine if the given user matches the given id.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $id
     * @return bool
     */
    public function userId(User $user, User $id)
    {
        return $user->id === $id->id; // Only the user itself
    }

    /**
     * Determine if the given user has the "User Subscription" role.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function userRole(User $user)
    {
        return $user->role === "User Subscription"; // User role
    }
}
