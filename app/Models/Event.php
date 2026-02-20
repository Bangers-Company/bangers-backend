<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function banner()
    {
        return $this->belongsTo(Media::class, "banner_media_id");
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'event_stages');
    }

    public function acts()
    {
        return $this->belongsToMany(Act::class, 'event_stage_acts')
            ->withPivot('stage_id', 'date')
            ->withTimestamps();
    }
}
