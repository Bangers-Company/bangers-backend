<?php

use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
