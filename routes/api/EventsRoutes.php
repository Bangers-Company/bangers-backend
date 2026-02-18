<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventController::class, 'index'])->name('events.index');
Route::post('events', [EventController::class, 'store'])->name('events.store');
Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
