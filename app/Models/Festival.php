<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Festival extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'location',
        'start_date',
        'end_date',
        'banner_media_id',
        'version',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'version' => 'integer',
    ];

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_media_id');
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function acts()
    {
        return $this->belongsToMany(Act::class, 'festival_acts')
            ->withPivot('announcement_date')
            ->withTimestamps();
    }
}
