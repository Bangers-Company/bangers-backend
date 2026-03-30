<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FriendshipService;

class FriendshipController extends Controller
{
    protected $friendshipService;

    public function __construct(FriendshipService $friendshipService)
    {
        $this->friendshipService = $friendshipService;
    }
    /**
     * POST /friends/{userId}
     * Send or auto-accept request
     */
    public function store(Request $request, User $user)
    {
        $friendship = $this->friendshipService->sendRequest($request->user(), $user->id);

        return (new FriendshipResource($friendship->load(['user1', 'user2'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * PUT /friends/{userId}/accept
     */
    public function accept(Request $request, User $user)
    {
        $friendship = $this->friendshipService->acceptRequest($request->user(), $user->id);

        return new FriendshipResource($friendship->load(['user1', 'user2']));
    }

    /**
     * PUT /friends/{userId}/reject
     */
    public function reject(Request $request, User $user)
    {
        $this->friendshipService->rejectRequest($request->user(), $user->id);

        return response()->json(['message' => 'Request rejected']);
    }

    /**
     * PUT /friends/{userId}/block
     */
    public function block(Request $request, User $user)
    {
        $this->friendshipService->blockUser($request->user(), $user->id);

        return response()->json(['message' => 'User blocked']);
    }

    /**
     * DELETE /friends/{userId}
     */
    public function destroy(Request $request, User $user)
    {
        $this->friendshipService->removeFriendship($request->user(), $user->id);

        return response()->json(['message' => 'Friendship removed']);
    }

    /**
     * GET /friends
     */
    public function index(Request $request)
    {
        $friends = $this->friendshipService->getFriends($request->user()->id);

        return UserResource::collection($friends);
    }

    /**
     * GET /friends/requests
     */
    public function requests(Request $request)
    {
        $requests = $this->friendshipService->getPendingRequests($request->user()->id);

        return FriendshipResource::collection($requests);
    }

    /**
     * GET /friends/users/{id}/friends
     */
    public function userFriends(Request $request, User $user)
    {
        $friends = $this->friendshipService->getFriends($user->id);

        return UserResource::collection($friends);
    }
}
