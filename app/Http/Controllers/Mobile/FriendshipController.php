<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function store(Request $request, $userId)
    {
        $user = $request->user();
        if ($user->id === $userId) abort(400, 'Cannot friend yourself');

        $target = User::findOrFail($userId);
        $u1 = min($user->id, $userId);
        $u2 = max($user->id, $userId);

        $existing = Friendship::where('user_id_1', $u1)->where('user_id_2', $u2)->first();
        if ($existing) return new FriendshipResource($existing->load(['user1', 'user2']));

        $status = $target->is_public ? 'accepted' : 'pending';

        $friendship = Friendship::create([
            'user_id_1' => $u1,
            'user_id_2' => $u2,
            'status' => $status,
            'requested_by' => $user->id,
        ]);

        return (new FriendshipResource($friendship->load(['user1', 'user2'])))
            ->response()
            ->setStatusCode(201);
    }

    public function accept(Request $request, $userId)
    {
        $user = $request->user();
        $u1 = min($user->id, $userId);
        $u2 = max($user->id, $userId);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->where('requested_by', $userId)
            ->firstOrFail();

        $friendship->update(['status' => 'accepted']);
        return new FriendshipResource($friendship->load(['user1', 'user2']));
    }

    public function reject(Request $request, $userId)
    {
        $user = $request->user();
        $u1 = min($user->id, $userId);
        $u2 = max($user->id, $userId);

        $friendship = Friendship::where('user_id_1', $u1)
            ->where('user_id_2', $u2)
            ->where('requested_by', $userId)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->delete();
        return response()->json(['message' => 'Request rejected']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $friends = Friendship::with(['user1', 'user2'])
            ->where('status', 'accepted')
            ->where(function($q) use ($user) {
                $q->where('user_id_1', $user->id)->orWhere('user_id_2', $user->id);
            })
            ->get()
            ->map(fn($f) => $f->getFriendOf($user->id));

        return UserResource::collection($friends);
    }

    public function requests(Request $request)
    {
        $user = $request->user();
        $requests = Friendship::with('requester')
            ->where('status', 'pending')
            ->where('requested_by', '!=', $user->id)
            ->where(function($q) use ($user) {
                $q->where('user_id_1', $user->id)->orWhere('user_id_2', $user->id);
            })
            ->get();

        return FriendshipResource::collection($requests);
    }

    public function destroy(Request $request, $userId)
    {
        $user = $request->user();
        $u1 = min($user->id, $userId);
        $u2 = max($user->id, $userId);
        Friendship::where('user_id_1', $u1)->where('user_id_2', $u2)->delete();
        return response()->json(['message' => 'Friendship removed']);
    }
}
