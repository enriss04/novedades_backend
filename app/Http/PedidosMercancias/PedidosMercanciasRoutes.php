<?php

use Illuminate\Support\Facades\Route;
use App\Http\PedidosMercancias\PedidosMercanciasController;

Route::prefix('pedidos_mercancias')->group(function () {
    Route::post('/get',     [PedidosMercanciasController::class, 'get']);
    Route::post('/first',   [PedidosMercanciasController::class, 'first']);
    Route::post('/create',  [PedidosMercanciasController::class, 'create']);
    Route::post('/destroy', [PedidosMercanciasController::class, 'destroy']);
});