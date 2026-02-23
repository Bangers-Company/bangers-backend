<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\ActResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use App\Models\Stage;
use App\Models\Media;

class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'counts' => [
                'events' => Event::count(),
                'artists' => Artist::count(),
                'acts' => Act::count(),
                'stages' => Stage::count(),
                'media' => Media::count(),
            ],
            'recent' => [
                'events' => EventResource::collection(Event::with('stages', 'banner')->latest()->take(5)->get()),
                'artists' => ArtistResource::collection(Artist::with('acts', 'image')->latest()->take(5)->get()),
                'acts' => ActResource::collection(Act::with('artists', 'stages')->latest()->take(5)->get()),
            ],
        ]);
    }

    public function MobileDashboard()
    {
        /**
         * will later be in format:
         * 'response' => [
         *      'attending_events' => EventResource::collection(Event::with('banner')->orderBy('start_date', 'desc')->get()),
         *      'suggested_events' => ArtistResource::collection(Event::with('banner')->latest()->take(10)->get()),
         *      'events_from_friends' => ActResource::collection(Event::with('banner')->latest()->take(10)->get()),'
         * ];
         *
         * Should later implement pagination; NOT USING THIS FUNCTION
         * Pagination is based on row EventController.
         * scrolling for example suggested should call /events/suggested
         * scrolling for example attending should call /events/attending
         * scrolling for example events_from_friends should call /events/events_from_friends
         *
         * Dashboard is only for initial data
         */
        return response()->json([
            'response' => [
                'events' => EventResource::collection(Event::with(['banner', 'stages', 'acts.artists'])->orderBy('start_date', 'desc')->latest()->take(10)->get())
            ],
        ]);
    }
}
