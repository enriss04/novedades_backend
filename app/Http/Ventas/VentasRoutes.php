<?php

use Illuminate\Support\Facades\Route;
use App\Http\Ventas\VentasController;

Route::prefix('ventas')->group(function () {
    Route::post('/get',     [VentasController::class, 'get']);
    Route::post('/first',   [VentasController::class, 'first']);
    Route::post('/select',  [VentasController::class, 'select']);
    Route::post('/create',  [VentasController::class, 'create']);
    Route::post('/update',  [VentasController::class, 'update']);
    Route::post('/destroy', [VentasController::class, 'destroy']);
});