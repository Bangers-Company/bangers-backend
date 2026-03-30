<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\Admin\UpdateUserRequest;
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

        $perPage = min(max((int) $request->query('size', 20), 1), 100);
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

        if (!$user->is_public && auth()->id() !== $user->id && !auth()->user()?->hasRole('admin')) {
            return new UserResource($user->only(['id', 'username', 'first_name', 'is_public']));
        }

        return new UserResource($user->load(['roles.permissions', 'profileMedia']));
    }

    /**
     * PUT /users/{id} (admin or self)
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->id !== $user->id && !$request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $user->update($request->validated());

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
