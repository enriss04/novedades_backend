<?php

use Illuminate\Support\Facades\Route;
use App\Http\Auth\AuthController;

Route::post('/create',      [AuthController::class, 'create']);
Route::post('/login',       [AuthController::class, 'login']);
Route::post('/reconnect',   [AuthController::class, 'reconnect']);
Route::post('/logout',      [AuthController::class, 'logout']);