<?php

use Illuminate\Support\Facades\Route;

Route::get('events/{id}', [EventController::class, 'show'])->name('events.mobile.id');
