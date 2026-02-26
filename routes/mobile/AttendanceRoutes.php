<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::put('events/{eventId}/attendance', [AttendanceController::class, 'update']);
    Route::delete('events/{eventId}/attendance', [AttendanceController::class, 'destroy']);
});
