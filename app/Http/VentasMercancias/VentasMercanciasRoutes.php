<?php

use Illuminate\Support\Facades\Route;
use App\Http\VentasMercancias\VentasMercanciasController;

Route::prefix('ventas_mercancias')->group(function () {
    Route::post('/get',     [VentasMercanciasController::class, 'get']);
    Route::post('/first',   [VentasMercanciasController::class, 'first']);
    Route::post('/create',  [VentasMercanciasController::class, 'create']);
    Route::post('/destroy', [VentasMercanciasController::class, 'destroy']);
});