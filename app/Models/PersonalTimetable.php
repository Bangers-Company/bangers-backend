<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PersonalTimetable extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'is_public',
        'version',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function entries(): BelongsToMany
    {
        return $this->belongsToMany(TimetableEntry::class, 'personal_timetable_entries', 'timetable_id', 'timetable_entry_id')
            ->withPivot(['time_range', 'is_attending']);
    }
}
