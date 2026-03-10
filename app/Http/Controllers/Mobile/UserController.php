<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return new UserResource($request->user()->load([
            'roles.permissions',
            'upcomingEvents.banner',
            'upcomingEvents.stages',
            'pastEvents.banner',
            'pastEvents.stages'
        ]));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'email' => 'email|unique:users,email,' . $user->id,
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'bio' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $user->update($request->only(['email', 'first_name', 'last_name', 'bio', 'is_public']));

        return new UserResource($user->load('roles.permissions'));
    }
}
