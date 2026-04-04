<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ["name", "bio", "genre", "image_media_id"];

    protected static function booted(): void
    {
        static::deleting(function (Artist $artist) {
            if ($artist->isForceDeleting()) {
                return;
            }
            $artist->acts()->detach();
        });
    }

    public function image(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, "image_media_id");
    }

    public function banner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, "banner_media_id");
    }

    public function genres(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }

    public function acts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Act::class, "act_artists");
    }
}
