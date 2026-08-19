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
        $user = $request->user();
        $user->load([
            'roles.permissions',
            'profileMedia',
            'upcomingEvents.banner',
            'upcomingEvents.stages',
            'pastEvents.banner',
            'pastEvents.stages',
            'pendingFriendRequests.requester'
        ]);
        
        // Count friendships (status accepted)
        $user->friend_count = \App\Models\Friendship::where(function($query) use ($user) {
            $query->where('user_id_1', $user->id)
                  ->orWhere('user_id_2', $user->id);
        })->where('status', 'accepted')->count();

        return new UserResource($user);
    }

    public function show(Request $request, $id)
    {
        $user = User::with(['roles', 'profileMedia'])->findOrFail($id);
        
        $isMe = $request->user()?->id === $user->id;
        $isFriend = $user->isFriendWith($request->user());
        $canSeeFullProfile = $user->is_public || $isMe || $isFriend;

        if ($canSeeFullProfile) {
            $user->load([
                'upcomingEvents.banner',
                'upcomingEvents.stages',
                'pastEvents.banner',
                'pastEvents.stages',
                'genres'
            ]);
        }

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
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'dob' => 'nullable|date',
            'is_public' => 'boolean',
            'profile_media_id' => 'nullable|uuid|exists:media,id',
            'genres' => 'array',
            'genres.*' => 'exists:genres,id',
        ]);

        $user->update($request->only(['email', 'first_name', 'last_name', 'bio', 'dob', 'is_public', 'profile_media_id']));

        if ($request->has('genres')) {
            $user->genres()->sync($request->genres);
        }

        return new UserResource($user->load(['roles.permissions', 'profileMedia', 'genres']));
    }
}
