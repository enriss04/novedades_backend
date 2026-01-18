<?php

use Illuminate\Support\Facades\Route;
use App\Http\Pedidos\PedidosController;

Route::prefix('pedidos')->group(function () {
    Route::post('/select',  [PedidosController::class, 'select']);
    Route::post('/get',     [PedidosController::class, 'get']);
    Route::post('/first',   [PedidosController::class, 'first']);
    Route::post('/create',  [PedidosController::class, 'create']);
    Route::post('/update',  [PedidosController::class, 'update']);
    Route::post('/destroy', [PedidosController::class, 'destroy']);
});