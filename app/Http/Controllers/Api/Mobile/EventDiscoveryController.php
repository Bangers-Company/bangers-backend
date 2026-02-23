<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventDiscoveryController extends Controller
{
    /**
     * Paginated list of suggested events for the user.
     */
    public function suggested(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Mock suggestion logic: Latest events for now
        $events = Event::with(['banner'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return EventResource::collection($events);
    }

    /**
     * Paginated list of events that friends are attending.
     */
    public function friends(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Mock friends attendance logic: Upcoming events for now
        $events = Event::with(['banner'])
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->paginate($perPage);

        return EventResource::collection($events);
    }
}
