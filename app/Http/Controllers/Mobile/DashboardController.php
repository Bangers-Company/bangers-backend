<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\UserResource;
use App\Models\Event;
use App\Models\Friendship;
use Illuminate\Http\Request;

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

        // Load everything onto the user object so UserResource can include it
        $user->load([
            'roles.permissions', 
            'profileMedia',
            'upcomingEvents' => function($query) use ($eventRelations) {
                $query->with($eventRelations)->withCount('attendees');
            },
            'pastEvents' => function($query) use ($eventRelations) {
                $query->with($eventRelations)->withCount('attendees');
            },
            'pendingFriendRequests.requester'
        ]);

        // Dashboard specific categories
        $attendingEvents = $user->upcomingEvents; // This relationship matches "going" in future
        
        // Load "interested" events separately for the dashboard
        $interestedEvents = $user->attendedEvents()
            ->with($eventRelations)
            ->withCount('attendees')
            ->wherePivot('status', 'interested')
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        // Suggested Events (Upcoming, ordered by date)
        $suggestedEvents = Event::with($eventRelations)
            ->withCount('attendees')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(10)
            ->get();

        // Friends Events (Mock logic: just some upcoming events for now)
        $friendsEvents = Event::with($eventRelations)
            ->withCount('attendees')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(10)
            ->get();

        // Friends Count
        $friendsCount = Friendship::where(function($query) use ($user) {
                $query->where('user_id_1', $user->id)
                      ->orWhere('user_id_2', $user->id);
            })
            ->where('status', 'accepted')
            ->count();
            
        $user->friend_count = $friendsCount;

        return response()->json([
            'data' => [
                'user' => new UserResource($user),
                'attending_events' => EventResource::collection($attendingEvents),
                'upcoming_events' => EventResource::collection($interestedEvents),
                'past_events' => EventResource::collection($user->pastEvents),
                'suggested_events' => EventResource::collection($suggestedEvents),
                'friends_events' => EventResource::collection($friendsEvents),
                'friends_count' => $friendsCount,
                'sync_timestamp' => now()->toIso8601String(),
            ],
            'meta' => [
                'discovery_endpoints' => [
                    'suggested' => route('api.mobile.events.suggested'),
                    'friends' => route('api.mobile.events.friends'),
                ]
            ]
        ]);
    }
}
