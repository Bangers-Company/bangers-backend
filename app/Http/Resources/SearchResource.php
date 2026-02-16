<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [];

        foreach (['festivals', 'artists', 'acts'] as $entity) {
            if (isset($this->resource[$entity])) {
                $paginator = $this->resource[$entity];
                $resourceClass = match ($entity) {
                    'festivals' => FestivalResource::class,
                    'artists' => ArtistResource::class,
                    'acts' => ActResource::class,
                };

                $response[$entity] = [
                    'data' => $resourceClass::collection($paginator->items()),
                    'meta' => [
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'per_page' => $paginator->perPage(),
                        'total' => $paginator->total(),
                    ],
                    'links' => [
                        'first' => $paginator->url(1),
                        'last' => $paginator->url($paginator->lastPage()),
                        'prev' => $paginator->previousPageUrl(),
                        'next' => $paginator->nextPageUrl(),
                    ],
                ];
            }
        }

        return $response;
    }
}
