<?php

namespace App\Services;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Events\FriendshipAccepted;
use App\Events\FriendRequestSent;

class FriendshipService
{
    /**
     * Get ordered user IDs (min, max).
     */
    protected function getOrderedIds($id1, $id2): array
    {
        return [min($id1, $id2), max($id1, $id2)];
    }

    /**
     * Send or auto-accept a friendship request.
     */
    public function sendRequest(User $user, string $targetId): Friendship
    {
        if ((string)$user->id === (string)$targetId) {
            abort(400, 'Cannot friend yourself');
        }

        $target = User::findOrFail($targetId);
        [$u1, $u2] = $this->getOrderedIds($user->id, $targetId);

        $existing = Friendship::where('user_id_1', $u1)->where('user_id_2', $u2)->first();
        if ($existing) {
            return $existing;
        }

        $status = 'pending';

        $friendship = Friendship::create([
            'user_id_1' => $u1,
            'user_id_2' => $u2,
            'status' => $status,
            'requested_by' => $user->id,
        ]);

        event(new FriendRequestSent($friendship));

        return $friendship;
    }

    /**
     * Accept a pending friendship request.
     */
    public function acceptRequest(User $user, string $targetId): Friendship
    {
        [$u1, $u2] = $this->getOrderedIds($user->id, $targetId);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->where('requested_by', $targetId)
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);

        event(new FriendshipAccepted($friendship));

        return $friendship;
    }

    /**
     * Reject or cancel a pending friendship request.
     */
    public function rejectRequest(User $user, string $targetId): void
    {
        [$u1, $u2] = $this->getOrderedIds($user->id, $targetId);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->delete();
    }

    /**
     * Block a user.
     */
    public function blockUser(User $user, string $targetId): void
    {
        [$u1, $u2] = $this->getOrderedIds($user->id, $targetId);

        Friendship::updateOrCreate(
            ['user_id_1' => $u1, 'user_id_2' => $u2],
            ['status' => 'blocked', 'requested_by' => $user->id]
        );
    }

    /**
     * Remove a friendship or unblock a user.
     */
    public function removeFriendship(User $user, string $targetId): void
    {
        [$u1, $u2] = $this->getOrderedIds($user->id, $targetId);

        Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->delete();
    }

    /**
     * Get all accepted friends for a user.
     */
    public function getFriends(string $userId): Collection
    {
        return Friendship::with(['user1.profileMedia', 'user2.profileMedia'])
            ->where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('user_id_1', $userId)->orWhere('user_id_2', $userId);
            })
            ->get()
            ->map(fn($f) => $f->getFriendOf($userId));
    }

    /**
     * Get pending friendship requests for a user.
     */
    public function getPendingRequests(string $userId): Collection
    {
        return Friendship::with(['requester.profileMedia', 'requester.roles'])
            ->where('status', 'pending')
            ->where('requested_by', '!=', $userId)
            ->where(function ($q) use ($userId) {
                $q->where('user_id_1', $userId)->orWhere('user_id_2', $userId);
            })
            ->get();
    }

    /**
     * Get friendship status between two users.
     */
    public function getStatus(string $userId1, string $userId2): string
    {
        if ((string)$userId1 === (string)$userId2) {
            return 'self';
        }

        [$u1, $u2] = $this->getOrderedIds($userId1, $userId2);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->first();

        if (!$friendship) {
            return 'none';
        }

        if ($friendship->status === 'accepted') {
            return 'accepted';
        }

        if ($friendship->status === 'blocked') {
            return 'blocked';
        }

        if ((string)$friendship->requested_by === (string)$userId1) {
            return 'pending_sent';
        }

        return 'pending_received';
    }
}
