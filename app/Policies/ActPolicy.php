<?php

namespace App\Policies;

use App\Models\Act;
use App\Models\User;

class ActPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Act $act): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_events') || $user->hasPermission('manage_content');
    }

    public function update(User $user, Act $act): bool
    {
        return $user->hasPermission('manage_events') || $user->hasPermission('manage_content');
    }

    public function delete(User $user, Act $act): bool
    {
        return $user->hasPermission('manage_events') || $user->hasPermission('manage_content');
    }
}
