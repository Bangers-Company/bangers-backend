<?php

use App\Http\Controllers\Mobile\TimetableController;
use App\Http\Controllers\Mobile\PersonalTimetableController;
use Illuminate\Support\Facades\Route;

// Official Timetable (Publiek)
Route::get('events/{event_id}/timetable', [TimetableController::class, 'show']);

// Personal Timetables
Route::prefix('personal-timetables')->group(function () {
    Route::post('/', [PersonalTimetableController::class, 'store']);
    Route::get('{event_id}', [PersonalTimetableController::class, 'show']);
    Route::put('{id}/entries', [PersonalTimetableController::class, 'updateEntries']);
    Route::delete('{id}', [PersonalTimetableController::class, 'destroy']);
    Route::post('{id}/entries/{entry_id}/toggle-attend', [PersonalTimetableController::class, 'toggleAttend']);
});
