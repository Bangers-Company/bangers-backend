<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipResource extends JsonResource
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
            'status' => $this->status,
            'requester_id' => $this->requester_id,
            'user1' => new UserResource($this->whenLoaded('user1')),
            'user2' => new UserResource($this->whenLoaded('user2')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
