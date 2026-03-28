<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Groups\StoreGroupRequest;
use App\Services\GroupService;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * POST /groups
     */
    public function store(StoreGroupRequest $request)
    {
        $group = $this->groupService->createGroup($request->user(), $request->validated());

        return response()->json($group->load(['owner', 'timetables']), 201);
    }

    /**
     * GET /groups
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->groups()->with(['owner', 'timetables'])->get());
    }

    /**
     * GET /groups/{group}
     */
    public function show(Request $request, Group $group)
    {
        Gate::authorize('view', $group);

        return response()->json($group->load(['owner', 'members', 'timetables']));
    }

    /**
     * PUT /groups/{group}
     */
    public function update(Request $request, Group $group)
    {
        Gate::authorize('update', $group);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->groupService->updateGroup($group, $request->only(['name', 'description']));

        return response()->json($group->load(['owner', 'timetables']));
    }

    /**
     * POST /groups/{group}/members
     */
    public function addMember(Request $request, Group $group)
    {
        Gate::authorize('addMember', $group);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|in:member,admin'
        ]);

        $this->groupService->addMember($group, $request->user_id, $request->role ?? 'member');

        return response()->json(['message' => 'Member added successfully']);
    }

    /**
     * DELETE /groups/{group}/members/{user}
     */
    public function removeMember(Request $request, Group $group, User $user)
    {
        Gate::authorize('removeMember', [$group, $user->id]);

        $this->groupService->removeMember($group, $user->id);

        return response()->json(['message' => 'Member removed successfully']);
    }

    /**
     * DELETE /groups/{group}
     */
    public function destroy(Request $request, Group $group)
    {
        Gate::authorize('delete', $group);

        $this->groupService->deleteGroup($group);

        return response()->json(['message' => 'Group deleted successfully']);
    }

    /**
     * POST /groups/{group}/accept
     */
    public function acceptInvitation(Request $request, Group $group)
    {
        $this->groupService->acceptInvitation($group, $request->user()->id);

        return response()->json(['message' => 'Invitation accepted']);
    }

    /**
     * POST /groups/{group}/reject
     */
    public function rejectInvitation(Request $request, Group $group)
    {
        $this->groupService->rejectInvitation($group, $request->user()->id);

        return response()->json(['message' => 'Invitation rejected']);
    }
}
