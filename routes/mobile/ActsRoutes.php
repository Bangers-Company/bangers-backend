<?php

use App\Http\Controllers\Api\Mobile\ActController;
use Illuminate\Support\Facades\Route;

Route::get('acts/{id}', [ActController::class, 'show'])->name('acts.mobile.show');
