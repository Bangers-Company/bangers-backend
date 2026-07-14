<?php

use App\Http\Controllers\Mobile\ActController;
use Illuminate\Support\Facades\Route;

Route::get('acts/{id}', [ActController::class, 'show'])->name('acts.mobile.show');
