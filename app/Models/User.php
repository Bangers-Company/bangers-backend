<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'dob',
        'bio',
        'profile_media_id',
        'is_public',
        'last_login_at',
        'version',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
        'last_login_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * RBAC Relationships
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class , 'user_roles');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function($q) use ($permission) {
            $q->where('name', $permission);
        })->exists();
    }

    /**
     * Friendship Relationships
     */
    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class , 'user_id_1')
            ->orWhere('user_id_2', $this->id);
    }

    /**
     * Attendance
     */
    public function attendedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class , 'user_event_attendance')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function profileMedia(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class , 'profile_media_id');
    }

    /**
     * Events the user is attending that are in the future or today
     */
    public function upcomingEvents()
    {
        return $this->attendedEvents()
            ->wherePivot('status', 'going')
            ->where(function ($query) {
                $query->where('end_date', '>=', now()->startOfDay())
                      ->orWhere(function ($sub) {
                          $sub->whereNull('end_date')
                              ->where('start_date', '>=', now()->startOfDay());
                      });
            })
            ->orderBy('start_date', 'asc');
    }

    /**
     * Events the user attended in the past
     */
    public function pastEvents()
    {
        return $this->attendedEvents()
            ->wherePivot('status', 'going')
            ->where('end_date', '<', now())
            ->orderBy('end_date', 'desc');
    }

    public function pendingFriendRequests(): HasMany
    {
        return $this->hasMany(Friendship::class , 'user_id_2')
            ->where('status', 'pending');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class , 'group_members')
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function markAsVerified(): void
    {
        $this->is_verified = true;
        $this->save();
    }

    /**
     * Music Genres selection.
     */
    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(UserDeviceToken::class);
    }

    /**
     * Specifies the user's FCM tokens for push notifications.
     */
    public function routeNotificationForFcm(): array
    {
        return $this->deviceTokens()
            ->latest('last_used_at')
            ->pluck('token')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Check if user is friends with another user.
     */
    public function isFriendWith(?User $user): bool
    {
        if (!$user) return false;

        return Friendship::where(function($q) use ($user) {
            $q->where('user_id_1', $this->id)->where('user_id_2', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('user_id_1', $user->id)->where('user_id_2', $this->id);
        })->where('status', 'accepted')->exists();
    }
}