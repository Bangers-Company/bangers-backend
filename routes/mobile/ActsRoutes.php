<?php

use Illuminate\Support\Facades\Route;

Route::get('acts/{id}', [ActController::class, 'MobileAct'])->name('acts.mobile.id');
