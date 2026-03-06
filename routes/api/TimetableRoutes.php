<?php

use App\Http\Controllers\Admin\TimetableController;
use Illuminate\Support\Facades\Route;

Route::prefix('timetables')->group(function () {
    Route::get('/', [TimetableController::class, 'index']);
    Route::get('{id}', [TimetableController::class, 'show']);
    Route::post('/', [TimetableController::class, 'store']);
    Route::put('{id}', [TimetableController::class, 'update']);
    Route::patch('{id}/publish', [TimetableController::class, 'publish']);
    Route::delete('{id}', [TimetableController::class, 'destroy']);
});
