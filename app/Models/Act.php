<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Act extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'version',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'act_artists');
    }

    public function festivals()
    {
        return $this->belongsToMany(Festival::class, 'festival_acts')
            ->withPivot('announcement_date')
            ->withTimestamps();
    }
}
