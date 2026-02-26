<?php

use App\Http\Controllers\Mobile\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('search', [SearchController::class, 'index'])->name('search.mobile');
