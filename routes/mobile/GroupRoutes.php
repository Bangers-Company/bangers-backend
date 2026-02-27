<?php

use App\Http\Controllers\Mobile\GroupController;
use App\Http\Controllers\Mobile\GroupTimetableController;
use Illuminate\Support\Facades\Route;

// Groups
Route::prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('{id}', [GroupController::class, 'show']);
    Route::delete('{id}', [GroupController::class, 'destroy']);

    Route::post('{id}/members', [GroupController::class, 'addMember']);
    Route::delete('{id}/members/{user_id}', [GroupController::class, 'removeMember']);

    // Group Timetables
    Route::prefix('{group_id}/timetables')->group(function () {
        Route::get('/', [GroupTimetableController::class, 'index']);
        Route::post('/', [GroupTimetableController::class, 'store']);
        Route::get('{id}', [GroupTimetableController::class, 'show']);
        Route::put('{id}/entries', [GroupTimetableController::class, 'updateEntries']);
        Route::delete('{id}', [GroupTimetableController::class, 'destroy']);
    });
});
