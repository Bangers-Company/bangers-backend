<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Events\GroupCreated;
use App\Events\GroupInvitationSent;
use App\Events\GroupInvitationAccepted;
use App\Exceptions\GroupMembershipException;

class GroupService
{
    /**
     * Create a new group with the owner as the first member.
     */
    public function createGroup(User $owner, array $data): Group
    {
        return DB::transaction(function () use ($owner, $data) {
            $group = Group::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'owner_id' => $owner->id,
                'event_id' => $data['event_id'] ?? null,
            ]);

            $group->members()->attach($owner->id, [
                'role' => 'owner',
                'invitation_status' => 'accepted'
            ]);

            if (!empty($data['user_ids'])) {
                foreach ($data['user_ids'] as $invitedId) {
                    if ($invitedId !== $owner->id) {
                        $group->members()->attach($invitedId, [
                            'role' => 'member',
                            'invitation_status' => 'pending'
                        ]);

                        $invitedUser = User::find($invitedId);
                        if ($invitedUser) {
                            event(new GroupInvitationSent($group, $invitedUser, $owner));
                        }
                    }
                }
            }

            event(new GroupCreated($group));

            return $group;
        });
    }

    /**
     * Get groups for a user, optionally filtered.
     */
    public function getGroupsForUser(User $user, array $filters = []): Collection
    {
        $status = $filters['status'] ?? null;
        
        // Only load lightweight metadata — entries are fetched on-demand per group timetable
        $query = $user->groups()
            ->withCount('members')
            ->with(['owner', 'timetables:id,group_id,event_id,name']);

        if ($status) {
            $query->wherePivot('invitation_status', $status);
        }

        if (isset($filters['event_id'])) {
            $query->where('event_id', $filters['event_id']);
        }

        return $query->get();
    }


    /**
     * Update group details.
     */
    public function updateGroup(Group $group, array $data): void
    {
        $group->update($data);
    }

    /**
     * Delete a group.
     */
    public function deleteGroup(Group $group): void
    {
        $group->delete();
    }

    /**
     * Add a member to the group.
     */
    public function addMember(Group $group, string $userId, string $role = 'member', ?User $inviter = null): void
    {
        $group->members()->syncWithoutDetaching([
            $userId => [
                'role' => $role,
                'invitation_status' => 'pending'
            ]
        ]);

        $invitedUser = User::find($userId);
        if ($invitedUser) {
            $inviterUser = $inviter ?? User::find($group->owner_id);
            if ($inviterUser) {
                event(new GroupInvitationSent($group, $invitedUser, $inviterUser));
            }
        }
    }

    /**
     * Remove a member from the group.
     */
    public function removeMember(Group $group, string $userId): void
    {
        if ($group->owner_id === $userId) {
            throw new GroupMembershipException('Cannot remove the group owner.');
        }

        if (!$group->members()->where('user_id', $userId)->exists()) {
            throw new GroupMembershipException('User is not a member of this group.');
        }

        $group->members()->detach($userId);
    }

    /**
     * Handle a user leaving a group or the owner deleting it.
     */
    public function leaveOrDeleteGroup(Group $group, User $user): string
    {
        if ($group->owner_id === $user->id) {
            $group->delete();
            return 'Group deleted for everyone';
        }

        // Member leaves the group
        DB::transaction(function () use ($group, $user) {
            foreach ($group->timetables as $timetable) {
                $timetable->attendingUsers()->detach($user->id);
            }
            $group->members()->detach($user->id);
        });

        return 'You have left the group';
    }

    /**
     * Accept a group invitation.
     */
    public function acceptInvitation(Group $group, string $userId): void
    {
        $exists = $group->members()
            ->where('user_id', $userId)
            ->wherePivot('invitation_status', 'pending')
            ->exists();

        if (!$exists) {
            throw new GroupMembershipException('Invitation not found or already accepted.');
        }

        $group->members()->updateExistingPivot($userId, [
            'invitation_status' => 'accepted'
        ]);

        $user = User::find($userId);
        if ($user) {
            event(new GroupInvitationAccepted($group, $user));
        }
    }

    /**
     * Reject a group invitation.
     */
    public function rejectInvitation(Group $group, string $userId): void
    {
        $exists = $group->members()
            ->where('user_id', $userId)
            ->wherePivot('invitation_status', 'pending')
            ->exists();

        if (!$exists) {
            throw new GroupMembershipException('Invitation not found or already accepted.');
        }

        $group->members()->detach($userId);
    }
}
