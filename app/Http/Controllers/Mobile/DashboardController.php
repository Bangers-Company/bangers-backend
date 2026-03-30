<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Mobile Dashboard: Returns the initial snapshot for the app.
     * Includes Attending events and summaries/metadata for discovery sections.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $eventRelations = [
            'banner', 
            'stages', 
            'acts.artists', 
            'attendees.roles', 
            'attendees.profileMedia'
        ];

        $dashboardData = Cache::flexible("user_{$user->id}_dashboard", [300, 600], function () use ($user, $eventRelations) {
            $user->load([
                'roles.permissions', 
                'profileMedia',
                'upcomingEvents' => function($query) use ($eventRelations) {
                    $query->with($eventRelations)->withUserStatus()->withCount('attendees');
                },
                'pastEvents' => function($query) use ($eventRelations) {
                    $query->with($eventRelations)->withUserStatus()->withCount('attendees');
                },
                'pendingFriendRequests.requester'
            ]);

            $interestedEvents = $user->attendedEvents()
                ->with($eventRelations)
                ->withUserStatus()
                ->withCount('attendees')
                ->wherePivot('status', 'interested')
                ->where('end_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->get();

            $suggestedEvents = Event::with($eventRelations)
                ->withUserStatus()
                ->withCount('attendees')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->limit(10)
                ->get();

            $friendsEvents = Event::with($eventRelations)
                ->withUserStatus()
                ->withCount('attendees')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->limit(10)
                ->get();

            $friendsCount = Friendship::where(function($query) use ($user) {
                    $query->where('user_id_1', $user->id)
                          ->orWhere('user_id_2', $user->id);
                })
                ->where('status', 'accepted')
                ->count();
                
            return [
                'user' => (new UserResource($user))->resolve(),
                'attending_events' => EventResource::collection($user->upcomingEvents)->resolve(),
                'upcoming_events' => EventResource::collection($interestedEvents)->resolve(),
                'past_events' => EventResource::collection($user->pastEvents)->resolve(),
                'suggested_events' => EventResource::collection($suggestedEvents)->resolve(),
                'friends_events' => EventResource::collection($friendsEvents)->resolve(),
                'friends_count' => $friendsCount,
                'sync_timestamp' => now()->toIso8601String(),
            ];
        });

        return response()->json([
            'data' => $dashboardData,
            'meta' => [
                'discovery_endpoints' => [
                    'suggested' => route('api.mobile.v1.events.suggested'),
                    'friends' => route('api.mobile.v1.events.friends'),
                ]
            ]
        ]);
    }
}
