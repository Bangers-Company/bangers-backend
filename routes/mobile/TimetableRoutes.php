<?php

use App\Http\Controllers\Mobile\TimetableController;
use App\Http\Controllers\Mobile\PersonalTimetableController;
use Illuminate\Support\Facades\Route;

// Official Timetable (Publiek)
Route::get('events/{event_id}/timetable', [TimetableController::class, 'show']);
Route::post('events/{event_id}/timetable/entries/{entry_id}/toggle-attend', [TimetableController::class, 'toggleAttend']);

