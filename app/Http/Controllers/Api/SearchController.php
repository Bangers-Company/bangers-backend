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
        $queryText = $request->input("query");
        $date = $request->input("date");
        $location = $request->input("location");
        $entities = $request->input("entities", [
            "festivals",
            "artists",
            "acts",
        ]);
        $perPage = min(max((int) $request->input("per_page", 15), 1), 100);

        if ($queryText) {
            $queryText = str_replace(["%", "_"], ["\\%", "\\_"], $queryText);
        }
        if ($location) {
            $location = str_replace(["%", "_"], ["\\%", "\\_"], $location);
        }

        if (is_string($entities)) {
            $entities = explode(",", $entities);
        }

        $results = [];

        if (in_array("festivals", $entities)) {
            $festivalQuery = Festival::query();
            if ($queryText) {
                $festivalQuery->where("name", "like", "%{$queryText}%");
            }
            if ($date) {
                $festivalQuery
                    ->whereDate("start_date", "<=", $date)
                    ->whereDate("end_date", ">=", $date);
            }
            if ($location) {
                $festivalQuery->where("location", "like", "%{$location}%");
            }
            $results["festivals"] = $festivalQuery->paginate(
                $perPage,
                ["*"],
                "festivals_page",
            );
        }

        if (in_array("artists", $entities)) {
            $artistQuery = Artist::query();
            if ($queryText) {
                $artistQuery->where("name", "like", "%{$queryText}%");
            }
            $results["artists"] = $artistQuery->paginate(
                $perPage,
                ["*"],
                "artists_page",
            );
        }

        if (in_array("acts", $entities)) {
            $actQuery = Act::query();
            if ($queryText) {
                $actQuery->where("name", "like", "%{$queryText}%");
            }
            $results["acts"] = $actQuery->paginate(
                $perPage,
                ["*"],
                "acts_page",
            );
        }

        return new SearchResource($results);
    }
}
