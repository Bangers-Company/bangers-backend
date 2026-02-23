<?php

use Illuminate\Support\Facades\Route;

Route::get('events/{id}', [EventController::class, 'MobileEvent'])->name('events.mobile.id');
