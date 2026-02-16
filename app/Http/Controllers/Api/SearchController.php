<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Artist;
use App\Models\Festival;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search across multiple entities with filtering.
     */
    public function search(Request $request)
    {
        $queryText = $request->input('query');
        $date = $request->input('date');
        $location = $request->input('location');
        $entities = $request->input('entities', ['festivals', 'artists', 'acts']);

        if (is_string($entities)) {
            $entities = explode(',', $entities);
        }

        $results = [];

        if (in_array('festivals', $entities)) {
            $festivalQuery = Festival::query();
            if ($queryText) {
                $festivalQuery->where('name', 'like', "%{$queryText}%");
            }
            if ($date) {
                $festivalQuery->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date);
            }
            if ($location) {
                $festivalQuery->where('location', 'like', "%{$location}%");
            }
            $results['festivals'] = $festivalQuery->get();
        }

        if (in_array('artists', $entities)) {
            $artistQuery = Artist::query();
            if ($queryText) {
                $artistQuery->where('name', 'like', "%{$queryText}%");
            }
            // Artists don't have date/location directly in this schema
            $results['artists'] = $artistQuery->get();
        }

        if (in_array('acts', $entities)) {
            $actQuery = Act::query();
            if ($queryText) {
                $actQuery->where('name', 'like', "%{$queryText}%");
            }
            // Acts linkage to festivals could allow date/location filtering, 
            // but for simplicity we filter by name for now unless specified otherwise.
            $results['acts'] = $actQuery->get();
        }

        return new SearchResource($results);
    }
}
