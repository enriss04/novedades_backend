<?php

use Illuminate\Support\Facades\Route;
use App\Http\Dashboard\DashboardController;

Route::prefix('dashboard')->group(function () {
    Route::post('/',        [DashboardController::class, 'get']);
    Route::post('/lines',   [DashboardController::class, 'getDataLines']);
});