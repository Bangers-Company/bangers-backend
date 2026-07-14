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
            'id' => $this->user_id_1 . '_' . $this->user_id_2,
            'user_id_1' => $this->user_id_1,
            'user_id_2' => $this->user_id_2,
            'status' => $this->status,
            'requested_by' => $this->requested_by,
            'requester' => new UserResource($this->whenLoaded('requester')),
            'user1' => new UserResource($this->whenLoaded('user1')),
            'user2' => new UserResource($this->whenLoaded('user2')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
