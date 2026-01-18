<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('account')->group(base_path('app/Http/Auth/AuthRoutes.php'));

Route::middleware(['request-validation'])->group(function () {
    require base_path('app/Http/Ventas/VentasRoutes.php');
    require base_path('app/Http/Pedidos/PedidosRoutes.php');
    require base_path('app/Http/Usuarios/UsuariosRoutes.php');
    require base_path('app/Http/Auth/AuthenticatedRoutes.php');
    require base_path('app/Http/Dashboard/DashboardRoutes.php');
    require base_path('app/Http/Inventario/InventarioRoutes.php');
    require base_path('app/Http/Proveedores/ProveedoresRoutes.php');
    require base_path('app/Http/PedidosMercancias/PedidosMercanciasRoutes.php');
    require base_path('app/Http/VentasMercancias/VentasMercanciasRoutes.php');
});

Route::get('/cache', function () {
    if (request('key') !== '1234') {
        abort(403, 'No autorizado');
    }

    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');

    return response()->json(['status' => 'ok']);
});