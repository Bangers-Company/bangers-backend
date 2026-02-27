<?php

use App\Http\Controllers\Mobile\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::put('events/{eventId}/attendance', [AttendanceController::class, 'update']);
    Route::delete('events/{eventId}/attendance', [AttendanceController::class, 'destroy']);
});
