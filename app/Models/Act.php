<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Act extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ["name", "description", "is_live"];

    protected $casts = [
        "is_live" => "boolean",
    ];

    protected static function booted(): void
    {
        static::deleting(function (Act $act) {
            if ($act->isForceDeleting()) {
                return;
            }
            $act->artists()->detach();
            $act->stages()->detach();
        });
    }

    public function artists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Artist::class, "act_artists");
    }

    public function stages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Stage::class, "event_stage_acts")
            ->withPivot('event_id', 'date')
            ->withTimestamps();
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class, "event_stage_acts")
            ->withPivot('stage_id', 'date')
            ->withTimestamps();
    }
}
