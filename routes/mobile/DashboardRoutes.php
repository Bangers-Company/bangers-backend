<?php

use App\Http\Controllers\Api\Mobile\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.mobile');
