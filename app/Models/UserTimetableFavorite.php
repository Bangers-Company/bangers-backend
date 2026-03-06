<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTimetableFavorite extends Model
{
    use HasFactory;

    protected $table = 'user_timetable_favorites';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'timetable_entry_id',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(TimetableEntry::class, 'timetable_entry_id');
    }
}
