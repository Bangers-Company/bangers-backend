<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasUuids, SoftDeletes;

    const UPDATED_AT = null;

    protected $fillable = [
        'owner_id',
        'type',
        'storage_key',
        'url',
        'mime_type',
        'size_bytes',
        'width',
        'height',
        'metadata',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];
}
