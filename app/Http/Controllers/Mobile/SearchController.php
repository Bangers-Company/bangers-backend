<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Act;
use App\Models\User;
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
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:100',
            'entities' => 'nullable',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $filters = $request->all();
        $filters['with_media'] = true;
        
        $results = $this->searchService->search($filters);

        return new SearchResource($results);
    }
}
