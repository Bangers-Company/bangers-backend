<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festival extends Model
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
        static::deleting(function (Festival $festival) {
            if ($festival->isForceDeleting()) {
                return;
            }
            $festival->acts()->detach();
        });
    }

    public function banner()
    {
        return $this->belongsTo(Media::class, "banner_media_id");
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function acts()
    {
        return $this->belongsToMany(Act::class, "festival_acts")
            ->withPivot("announcement_date")
            ->withTimestamps();
    }
}
