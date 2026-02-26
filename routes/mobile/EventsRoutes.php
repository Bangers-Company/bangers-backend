<?php

use App\Http\Controllers\Mobile\EventController;
use App\Http\Controllers\Mobile\EventDiscoveryController;
use Illuminate\Support\Facades\Route;

Route::get('events/suggested', [EventDiscoveryController::class, 'suggested'])->name('events.suggested');
Route::get('events/friends', [EventDiscoveryController::class, 'friends'])->name('events.friends');
Route::get('events/{id}', [EventController::class, 'show'])->name('events.mobile.show');
