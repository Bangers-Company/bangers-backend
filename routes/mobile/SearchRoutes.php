<?php

use Illuminate\Support\Facades\Route;

Route::get('search', [SearchController::class, 'MobileSearch'])->name('search.mobile');
