<?php

use App\Http\Controllers\Mobile\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::prefix('favorites')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('{timetable_entry_id}', [FavoriteController::class, 'store']);
    Route::delete('{timetable_entry_id}', [FavoriteController::class, 'destroy']);
});
