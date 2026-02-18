<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Act extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ["name", "description"];

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

    public function artists()
    {
        return $this->belongsToMany(Artist::class, "act_artists");
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, "stage_acts")
            ->withPivot('created_at');
    }
}
