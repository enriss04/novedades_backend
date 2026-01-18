<?php

use Illuminate\Support\Facades\Route;
use App\Http\Auth\AuthController;
use App\Http\Usuarios\UsuariosController;

Route::prefix('usuarios')->group(function () {
    Route::post('/get',     [UsuariosController::class, 'get']);
    Route::post('/create',  [AuthController::class, 'create']);
    Route::post('/update',  [AuthController::class, 'update']);
    Route::post('/destroy', [UsuariosController::class, 'destroy']);
    Route::post('/update_password', [AuthController::class, 'updatePassword']);
});