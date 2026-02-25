<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * GET /users?page=&size= (admin only)
     */
    public function index(Request $request)
    {
        Gate::authorize('manage_users');

        $perPage = $request->query('size', 20);
        return UserResource::collection(User::with('roles')->paginate($perPage));
    }

    /**
     * GET /users/me
     */
    public function me(Request $request)
    {
        return new UserResource($request->user()->load('roles.permissions'));
    }

    /**
     * GET /users/{id}
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // If profile is private and not self/admin, maybe hide some info
        // For now returning basic user info
        return new UserResource($user->load(['roles.permissions', 'profileMedia']));
    }

    /**
     * PUT /users/{id} (admin or self)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->id !== $user->id && !$request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'email' => 'email|unique:users,email,' . $user->id,
            'username' => 'string|unique:users,username,' . $user->id,
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'dob' => 'nullable|date',
            'bio' => 'nullable|string',
            'is_public' => 'boolean',
            'profile_media_id' => 'nullable|uuid|exists:media,id',
        ]);

        $user->update($request->only([
            'email',
            'username',
            'first_name',
            'last_name',
            'dob',
            'bio',
            'is_public',
            'profile_media_id'
        ]));

        return new UserResource($user->load('roles.permissions'));
    }

    /**
     * DELETE /users/{id} (admin only)
     */
    public function destroy($id)
    {
        Gate::authorize('manage_users');

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    /**
     * POST /users/{id}/roles/{roleId} (admin only)
     */
    public function assignRole($id, $roleId)
    {
        Gate::authorize('manage_users');

        $user = User::findOrFail($id);
        $user->roles()->syncWithoutDetaching([$roleId]);

        return response()->json([$user->id, $roleId]);
    }

    /**
     * DELETE /users/{id}/roles/{roleId} (admin only)
     */
    public function removeRole($id, $roleId)
    {
        Gate::authorize('manage_users');

        $user = User::findOrFail($id);
        $user->roles()->detach($roleId);

        return response()->json([$user->id, $roleId]);
    }
}
