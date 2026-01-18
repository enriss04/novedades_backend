<?php

use Illuminate\Support\Facades\Route;
use App\Http\Inventario\InventarioController;

Route::prefix('inventario')->group(function () {
    Route::post('/get',     [InventarioController::class, 'get']);
    Route::post('/first',   [InventarioController::class, 'first']);
    Route::post('/create',  [InventarioController::class, 'create']);
    Route::post('/update',  [InventarioController::class, 'update']);
    Route::post('/destroy', [InventarioController::class, 'destroy']);
});