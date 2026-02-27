<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupTimetable extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'group_id',
        'event_id',
        'name',
        'version',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function entries(): BelongsToMany
    {
        return $this->belongsToMany(TimetableEntry::class, 'group_timetable_entries', 'group_timetable_id', 'timetable_entry_id')
            ->withPivot('added_by')
            ->withTimestamps();
    }
}
