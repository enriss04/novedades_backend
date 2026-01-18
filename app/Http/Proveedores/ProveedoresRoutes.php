<?php

use Illuminate\Support\Facades\Route;
use App\Http\Proveedores\ProveedoresController;

Route::prefix('proveedores')->group(function () {
    Route::post('/get',     [ProveedoresController::class, 'get']);
    Route::post('/first',   [ProveedoresController::class, 'first']);
    Route::post('/create',  [ProveedoresController::class, 'create']);
    Route::post('/update',  [ProveedoresController::class, 'update']);
    Route::post('/destroy', [ProveedoresController::class, 'destroy']);
});