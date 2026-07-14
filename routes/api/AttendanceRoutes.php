<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::put('events/{eventId}/attendance', [AttendanceController::class, 'update']);
    Route::delete('events/{eventId}/attendance', [AttendanceController::class, 'destroy']);
    Route::get('events/{eventId}/attendees', [AttendanceController::class, 'index']);
    Route::get('users/{id}/events', [AttendanceController::class, 'userEvents']);
});
