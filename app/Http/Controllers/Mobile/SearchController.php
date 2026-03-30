<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use App\Models\User;
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

    /**
     * Universal Mobile Search across Events, Artists, and Acts.
     */
    public function index(SearchRequest $request)
    {
        $filters = $request->validated();
        $filters['with_media'] = true;
        
        $results = $this->searchService->search($filters);

        return new SearchResource($results);
    }
}
