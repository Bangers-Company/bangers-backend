<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Genre extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Users that have this genre.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'genreable');
    }

    /**
     * Events that have this genre.
     */
    public function events(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'genreable');
    }

    /**
     * Artists that have this genre.
     */
    public function artists(): MorphToMany
    {
        return $this->morphedByMany(Artist::class, 'genreable');
    }

    /**
     * Acts that have this genre.
     */
    public function acts(): MorphToMany
    {
        return $this->morphedByMany(Act::class, 'genreable');
    }
}
