<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ["event_id", "name", "description"];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_stages');
    }

    public function acts()
    {
        return $this->belongsToMany(Act::class, "event_stage_acts")
            ->withPivot('event_id', 'date')
            ->withTimestamps();
    }
}
