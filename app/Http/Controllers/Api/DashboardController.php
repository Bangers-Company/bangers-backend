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
}
