<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'version' => $this->version,
            'banner' => new MediaResource($this->whenLoaded('banner')),
            'stages' => StageResource::collection($this->whenLoaded('stages')),
            'acts' => ActResource::collection($this->whenLoaded('acts')),
            'attendees' => UserResource::collection($this->whenLoaded('attendees')),
            'attendee_count' => $this->whenCounted('attendees'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
