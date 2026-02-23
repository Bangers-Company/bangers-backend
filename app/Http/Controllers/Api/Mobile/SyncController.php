<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\ActResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    /**
     * Delta sync for Events updated since a given timestamp.
     */
    public function events(Request $request)
    {
        $since = $request->input('since');
        $query = Event::with(['banner', 'stages', 'acts.artists'])->withTrashed();

        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        return EventResource::collection($query->get());
    }

    /**
     * Delta sync for Artists updated since a given timestamp.
     */
    public function artists(Request $request)
    {
        $since = $request->input('since');
        $query = Artist::with(['image'])->withTrashed();

        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        return ArtistResource::collection($query->get());
    }

    /**
     * Delta sync for Acts updated since a given timestamp.
     */
    public function acts(Request $request)
    {
        $since = $request->input('since');
        $query = Act::with(['artists.image'])->withTrashed();

        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        return ActResource::collection($query->get());
    }
}
