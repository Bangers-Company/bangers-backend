<?php

namespace App\Http\Controllers\Mobile;

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
        $limit = min((int) $request->input('limit', 500), 500);
        $query = Event::with(['banner', 'stages', 'acts.artists'])->withUserStatus()->withTrashed();

        if ($since) {
            if (is_numeric($since)) {
                $since = strlen((string)$since) >= 13
                    ? \Illuminate\Support\Carbon::createFromTimestampMs($since)
                    : \Illuminate\Support\Carbon::createFromTimestamp($since);
            }
            $query->where('updated_at', '>', $since);
        }

        $results = $query->limit($limit + 1)->get();
        $hasMore = $results->count() > $limit;

        if ($hasMore) {
            $results = $results->take($limit);
        }

        return EventResource::collection($results)
            ->additional([
                'sync_timestamp' => now()->toIso8601String(),
                'has_more' => $hasMore,
            ]);
    }

    /**
     * Delta sync for Artists updated since a given timestamp.
     */
    public function artists(Request $request)
    {
        $since = $request->input('since');
        $limit = min((int) $request->input('limit', 500), 500);
        $query = Artist::with(['image'])->withTrashed();

        if ($since) {
            if (is_numeric($since)) {
                $since = strlen((string)$since) >= 13
                    ? \Illuminate\Support\Carbon::createFromTimestampMs($since)
                    : \Illuminate\Support\Carbon::createFromTimestamp($since);
            }
            $query->where('updated_at', '>', $since);
        }

        $results = $query->limit($limit + 1)->get();
        $hasMore = $results->count() > $limit;

        if ($hasMore) {
            $results = $results->take($limit);
        }

        return ArtistResource::collection($results)
            ->additional([
                'sync_timestamp' => now()->toIso8601String(),
                'has_more' => $hasMore,
            ]);
    }

    /**
     * Delta sync for Acts updated since a given timestamp.
     */
    public function acts(Request $request)
    {
        $since = $request->input('since');
        $limit = min((int) $request->input('limit', 500), 500);
        $query = Act::with(['artists.image'])->withTrashed();

        if ($since) {
            if (is_numeric($since)) {
                $since = strlen((string)$since) >= 13
                    ? \Illuminate\Support\Carbon::createFromTimestampMs($since)
                    : \Illuminate\Support\Carbon::createFromTimestamp($since);
            }
            $query->where('updated_at', '>', $since);
        }

        $results = $query->limit($limit + 1)->get();
        $hasMore = $results->count() > $limit;

        if ($hasMore) {
            $results = $results->take($limit);
        }

        return ActResource::collection($results)
            ->additional([
                'sync_timestamp' => now()->toIso8601String(),
                'has_more' => $hasMore,
            ]);
    }
}
