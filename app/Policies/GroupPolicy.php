<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GroupPolicy
{
    public function view(User $user, Group $group): bool
    {
        return $group->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Group $group): bool
    {
        return $group->members()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function delete(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id;
    }

    public function addMember(User $user, Group $group): bool
    {
        return $this->update($user, $group);
    }

    public function removeMember(User $user, Group $group, string $targetUserId): bool
    {
        if ($user->id === $targetUserId) {
            return true; // Self-leave
        }
        return $this->update($user, $group);
    }
}
