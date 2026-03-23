<?php

use App\Http\Controllers\Mobile\GroupController;
use App\Http\Controllers\Mobile\GroupTimetableController;
use Illuminate\Support\Facades\Route;

// Groups
Route::prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('{group}', [GroupController::class, 'show']);
    Route::put('{group}', [GroupController::class, 'update']);
    Route::delete('{group}', [GroupController::class, 'destroy']);

    Route::post('{group}/members', [GroupController::class, 'addMember']);
    Route::delete('{group}/members/{user}', [GroupController::class, 'removeMember']);
    Route::post('{group}/accept', [GroupController::class, 'acceptInvitation']);
    Route::post('{group}/reject', [GroupController::class, 'rejectInvitation']);

    // Group Timetables
    Route::prefix('{group}/timetables')->group(function () {
        Route::get('/', [GroupTimetableController::class, 'index']);
        Route::post('/', [GroupTimetableController::class, 'store']);
        Route::get('{timetable}', [GroupTimetableController::class, 'show']);
        Route::put('{timetable}/entries', [GroupTimetableController::class, 'updateEntries']);
        Route::delete('{timetable}', [GroupTimetableController::class, 'destroy']);
        Route::post('{timetable}/entries/{entry}/toggle-attend', [GroupTimetableController::class, 'toggleAttend']);
        Route::get('{timetable}/entries/{entry}/attendance', [GroupTimetableController::class, 'getAttendance']);
    });
});
