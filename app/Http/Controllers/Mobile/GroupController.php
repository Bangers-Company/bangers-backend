<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * POST /groups
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $user = $request->user();

        $group = DB::transaction(function () use ($request, $user) {
            $group = Group::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => $user->id,
            ]);

            $group->members()->attach($user->id, [
                'role' => 'owner',
                'invitation_status' => 'accepted'
            ]);

            if ($request->has('user_ids')) {
                foreach ($request->user_ids as $invitedId) {
                    if ($invitedId !== $user->id) {
                        $group->members()->attach($invitedId, [
                            'role' => 'member',
                            'invitation_status' => 'pending'
                        ]);
                    }
                }
            }

            return $group;
        });

        return response()->json($group->load('owner'), 201);
    }

    /**
     * GET /groups
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->groups()->with('owner')->get());
    }

    /**
     * GET /groups/{id}
     */
    public function show(Request $request, $id)
    {
        $group = Group::with(['owner', 'members'])->findOrFail($id);

        if (!$group->members->contains($request->user()->id)) {
            abort(403, 'You are not a member of this group.');
        }

        return response()->json($group);
    }

    /**
     * POST /groups/{id}/members
     */
    public function addMember(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // Only owner or admin can add members
        $me = $group->members()->where('user_id', $request->user()->id)->first();
        if (!$me || !in_array($me->pivot->role, ['owner', 'admin'])) {
            abort(403, 'Only owners or admins can add members.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,member',
        ]);

        $group->members()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role]
        ]);

        return response()->json(['message' => 'Member added']);
    }

    /**
     * DELETE /groups/{id}/members/{user_id}
     */
    public function removeMember(Request $request, $id, $userId)
    {
        $group = Group::findOrFail($id);

        // Only owner or admin can remove members (except self)
        $me = $group->members()->where('user_id', $request->user()->id)->first();
        if (!$me || (!in_array($me->pivot->role, ['owner', 'admin']) && $request->user()->id !== $userId)) {
            abort(403, 'Unauthorized.');
        }

        // Cannot remove the owner
        if ($group->owner_id === $userId) {
            abort(400, 'Cannot remove the group owner.');
        }

        $group->members()->detach($userId);

        return response()->json(['message' => 'Member removed']);
    }

    /**
     * DELETE /groups/{id}
     */
    public function destroy(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if ($group->owner_id !== $request->user()->id) {
            abort(403, 'Only the owner can delete the group.');
        }

        $group->delete();

        return response()->json(['message' => 'Group deleted']);
    }

    /**
     * POST /groups/{id}/accept
     */
    public function acceptInvitation(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $userId = $request->user()->id;

        $membership = $group->members()->where('user_id', $userId)->first();
        if (!$membership) {
            abort(404, 'Invitation not found.');
        }

        $group->members()->updateExistingPivot($userId, [
            'invitation_status' => 'accepted'
        ]);

        return response()->json(['message' => 'Invitation accepted']);
    }

    /**
     * POST /groups/{id}/reject
     */
    public function rejectInvitation(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $userId = $request->user()->id;

        $membership = $group->members()->where('user_id', $userId)->first();
        if (!$membership) {
            abort(404, 'Invitation not found.');
        }

        $group->members()->detach($userId);

        return response()->json(['message' => 'Invitation rejected']);
    }
}
