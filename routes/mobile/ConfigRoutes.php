<?php

use App\Http\Controllers\Mobile\ConfigController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'config'], function () {
    Route::get('features', [ConfigController::class, 'features']);
});
