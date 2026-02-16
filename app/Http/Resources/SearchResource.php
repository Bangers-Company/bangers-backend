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
        // This resource expects a structured array from SearchController
        return [
            'festivals' => FestivalResource::collection($this->resource['festivals'] ?? []),
            'artists' => ArtistResource::collection($this->resource['artists'] ?? []),
            'acts' => ActResource::collection($this->resource['acts'] ?? []),
        ];
    }
}
