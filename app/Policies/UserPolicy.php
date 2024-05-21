<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;

class UserPolicy
{
    public function admin(User $user)
    {
        return $user->role === "Admin"; // Only admins can view all users
    }

    public function admin_userId(User $user, User $id)
    {
        return $user->role === "Admin" || $user->id === $id->id;
    }

    public function userId(User $user, User $id)
    {
        return $user->id === $id->id;
    }

    public  function userRole(User $user)
    {
        return $user->role === "Subscription User";
    }
}
