<?php

use Illuminate\Support\Facades\Route;

Route::get('search', [SearchController::class, 'mobileSearch'])->name('search.mobile');
