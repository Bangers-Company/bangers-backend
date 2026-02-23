<?php

use App\Http\Controllers\Api\Mobile\SyncController;
use Illuminate\Support\Facades\Route;

Route::prefix('sync')->name('sync.')->group(function () {
    Route::get('events', [SyncController::class, 'events'])->name('events');
    Route::get('artists', [SyncController::class, 'artists'])->name('artists');
    Route::get('acts', [SyncController::class, 'acts'])->name('acts');
});
