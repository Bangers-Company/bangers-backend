<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\LineupSyncController;
use Illuminate\Support\Facades\Route;

Route::apiResource('events', EventController::class);
Route::post('events/{event}/lineup-sync', [LineupSyncController::class, 'sync'])->name('events.lineup-sync');
