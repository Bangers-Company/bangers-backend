<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "name",
        "description",
        "location",
        "start_date",
        "end_date",
        "banner_media_id",
    ];

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date",
        "version" => "integer",
    ];

    protected static function booted(): void
    {
        static::deleting(function (Event $event) {
            if ($event->isForceDeleting()) {
                return;
            }
            // Logic for cascading deletes to stages/acts can be handled here if needed,
            // but database cascades are already in place.
        });
    }

    public function scopeWithUserStatus($query, $userId = null)
    {
        $userId = $userId ?: auth()->id();

        if (!$userId) {
            return $query;
        }

        return $query->addSelect([
            'user_status' => DB::table('user_event_attendance')
                ->select('status')
                ->whereColumn('event_id', 'events.id')
                ->where('user_id', $userId)
                ->limit(1)
        ]);
    }

    public function banner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, "banner_media_id");
    }

    public function stages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Stage::class, 'event_stages');
    }

    public function acts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Act::class, 'event_stage_acts')
            ->withPivot('stage_id', 'date')
            ->withTimestamps();
    }

    public function attendees(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_event_attendance')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function officialTimetable(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EventTimetable::class)
            ->where('is_official', true)
            ->where('is_public', true);
    }

    public function genres(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }
}
