<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
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

        // Load attending events (status: going) - Only future and today
        $attendingEvents = $user ? $user->attendedEvents()
            ->with(['banner', 'stages', 'acts.artists'])
            ->wherePivot('status', 'going')
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get() : collect();

        // Load upcoming (interested) events (status: interested) - Only future and today
        $interestedEvents = $user ? $user->attendedEvents()
            ->with(['banner', 'stages', 'acts.artists'])
            ->wherePivot('status', 'interested')
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get() : collect();

        return response()->json([
            'data' => [
                'attending_events' => EventResource::collection($attendingEvents),
                'upcoming_events' => EventResource::collection($interestedEvents),
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
