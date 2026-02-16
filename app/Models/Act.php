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
            $act->festivals()->detach();
        });
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, "act_artists");
    }

    public function festivals()
    {
        return $this->belongsToMany(Festival::class, "festival_acts")
            ->withPivot("announcement_date")
            ->withTimestamps();
    }
}
