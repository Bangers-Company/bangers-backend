<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Artist;
use App\Models\Event;
use App\Models\User;
use App\Http\Resources\SearchResource;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;

use App\Services\SearchService;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(SearchRequest $request)
    {
        $results = $this->searchService->search($request->validated());

        return new SearchResource($results);
    }

    /**
     * Mobile search (with images)
     */
    public function mobileSearch(SearchRequest $request)
    {
        $filters = $request->validated();
        $filters['with_media'] = true;
        $results = $this->searchService->search($filters);

        return new SearchResource($results);
    }
}
