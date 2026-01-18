<?php

use Illuminate\Support\Facades\Route;
use App\Http\Auth\AuthController;

Route::prefix('account')->group(function () {
    Route::post('/update_password', [AuthController::class, 'updatePassword']);
    Route::post('/update',          [AuthController::class, 'update']);
});