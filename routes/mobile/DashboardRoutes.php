<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'MobileDashboard'])->name('dashboard.mobile');
