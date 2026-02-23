<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Universal Mobile Search across Events, Artists, and Acts.
     */
    public function index(Request $request)
    {
        $queryText = $request->input('query');
        $entities = $request->input('entities', ['events', 'artists', 'acts']);
        $perPage = $request->input('per_page', 10);

        $results = [];

        if (in_array('events', $entities)) {
            $eventQuery = Event::with('banner');
            if ($queryText) {
                $eventQuery->where('name', 'ilike', "%{$queryText}%")
                          ->orWhere('location', 'ilike', "%{$queryText}%");
            }
            $results['events'] = $eventQuery->paginate($perPage, ['*'], 'events_page');
        }

        if (in_array('artists', $entities)) {
            $artistQuery = Artist::with('image');
            if ($queryText) {
                $artistQuery->where('name', 'ilike', "%{$queryText}%");
            }
            $results['artists'] = $artistQuery->paginate($perPage, ['*'], 'artists_page');
        }

        if (in_array('acts', $entities)) {
            $actQuery = Act::query();
            if ($queryText) {
                $actQuery->where('name', 'ilike', "%{$queryText}%");
            }
            $results['acts'] = $actQuery->paginate($perPage, ['*'], 'acts_page');
        }

        return new SearchResource($results);
    }
}
