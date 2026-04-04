<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->when($this->id === $request->user()?->id || $request->user()?->hasRole('admin'), $this->email),
            'username' => $this->username,
            'first_name' => $this->when($this->id === $request->user()?->id || $request->user()?->hasRole('admin'), $this->first_name),
            'last_name' => $this->when($this->id === $request->user()?->id || $request->user()?->hasRole('admin'), $this->last_name),
            'dob' => $this->when($this->id === $request->user()?->id || $request->user()?->hasRole('admin'), $this->dob),
            'last_login_at' => $this->when($this->id === $request->user()?->id || $request->user()?->hasRole('admin'), $this->last_login_at),
            'bio' => $this->bio,
            'is_public' => (bool) $this->is_public,
            'genres' => GenreResource::collection($this->whenLoaded('genres')),
            'profile_media_id' => $this->profile_media_id,
            'profile_media_url' => $this->profileMedia?->url,
            'friends_count' => $this->whenNotNull($this->friend_count),
            'stats' => [
                'upcoming_count' => (int) ($this->upcoming_events_count ?? ($this->upcomingEvents ? $this->upcomingEvents->count() : 0)),
                'past_count' => (int) ($this->past_events_count ?? ($this->pastEvents ? $this->pastEvents->count() : 0)),
            ],
            'upcoming_events' => EventResource::collection($this->whenLoaded('upcomingEvents')),
            'past_events' => EventResource::collection($this->whenLoaded('pastEvents')),
            'friend_requests' => FriendshipResource::collection($this->whenLoaded('pendingFriendRequests')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => $this->whenLoaded('roles', function() {
                return $this->roles->flatMap->permissions->pluck('name')->unique()->values();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
