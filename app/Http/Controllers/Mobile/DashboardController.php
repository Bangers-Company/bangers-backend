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
        // For now, since user login is not implemented, 'attending' is empty or mocked.
        // We will return upcoming events as the primary focus for the snapshot.

        $upcomingEvents = Event::with(['banner', 'stages', 'acts.artists'])
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        return response()->json([
            'data' => [
                'attending_events' => [], // To be implemented with user relations
                'upcoming_events' => EventResource::collection($upcomingEvents),
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
