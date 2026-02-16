<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'festival_id',
        'name',
        'description',
        'version',
    ];

    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }
}
