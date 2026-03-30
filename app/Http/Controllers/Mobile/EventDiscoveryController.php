<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

use App\Services\SearchService;

class EventDiscoveryController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Paginated list of suggested events for the user.
     */
    public function suggested(Request $request)
    {
        $perPage = min(max((int) $request->input('per_page', 10), 1), 100);

        $events = $this->searchService->getSuggestedEvents($request->user(), $perPage);

        return EventResource::collection($events);
    }

    /**
     * Paginated list of events that friends are attending.
     */
    public function friends(Request $request)
    {
        $perPage = min(max((int) $request->input('per_page', 10), 1), 100);

        $events = $this->searchService->getFriendsEvents($request->user(), $perPage);

        return EventResource::collection($events);
    }
}
