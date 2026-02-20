<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'event_id' => $this->whenPivotLoaded('event_stages', function () {
                return $this->pivot->event_id;
            }) ?: $this->whenPivotLoaded('event_stage_acts', function () {
                return $this->pivot->event_id;
            }),
            'events' => EventResource::collection($this->whenLoaded('events')),
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
