<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FriendshipService;
use Illuminate\Support\Facades\Log;

class FriendshipController extends Controller
{
    protected $friendshipService;

    public function __construct(FriendshipService $friendshipService)
    {
        $this->friendshipService = $friendshipService;
    }
    public function store(Request $request, User $user)
    {
        $friendship = $this->friendshipService->sendRequest($request->user(), $user->id);

        return (new FriendshipResource($friendship->load(['user1', 'user2'])))
            ->response()
            ->setStatusCode(201);
    }

    public function accept(Request $request, User $user)
    {
        $friendship = $this->friendshipService->acceptRequest($request->user(), $user->id);

        return new FriendshipResource($friendship->load(['user1', 'user2']));
    }

    public function reject(Request $request, User $user)
    {
        $this->friendshipService->rejectRequest($request->user(), $user->id);

        return response()->json(['message' => 'Request rejected']);
    }

    public function index(Request $request)
    {
        $friends = $this->friendshipService->getFriends($request->user()->id);

        return UserResource::collection($friends);
    }

    public function userFriends(Request $request, User $user)
    {
        $friends = $this->friendshipService->getFriends($user->id);

        return UserResource::collection($friends);
    }

    public function requests(Request $request)
    {
        $requests = $this->friendshipService->getPendingRequests($request->user()->id);

        return FriendshipResource::collection($requests);
    }

    public function destroy(Request $request, User $user)
    {
        $this->friendshipService->removeFriendship($request->user(), $user->id);

        return response()->json(['message' => 'Friendship removed']);
    }

    public function status(Request $request, User $user)
    {
        $status = $this->friendshipService->getStatus($request->user()->id, $user->id);

        return response()->json(['status' => $status]);
    }
}
