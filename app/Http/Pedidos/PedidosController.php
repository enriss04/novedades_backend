<?php

namespace App\Http\Pedidos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Pedidos\PedidosValidations;

use App\Models\Proveedor;
use App\Models\Dashboard;
use App\Models\Inventario;
use App\Http\Pedidos\useCases\getPedido;
use App\Http\Pedidos\useCases\firstPedido;
use App\Http\PedidosMercancias\useCases\getPedidoMercancia;

class PedidosController extends Controller
{

    protected $pedidos_validations;

    public function __construct(PedidosValidations $pedidos_validations)
    {
        $this->pedidos_validations = $pedidos_validations;
    }

    public function select(Request $request)
    {
        $proveedor = Proveedor::getSelect($request);
        $inventario = Inventario::getSelect($request);

        return response()->json(["msg" => "Pedidos listados", "data" => [
            "proveedores" => $proveedor,
            "inventario" => $inventario,
        ],  "status" => true]);
    }

    public function get(Request $request)
    {
        $data = getPedido::get($request);

        $dashboard = Dashboard::getTotalVentas((object)[
            'table' => 'pedidos', 
            'get_data' => $request->get_data
        ]);

        return response()->json(["msg" => "Pedidos listados", "data" => [
            "data" => $data, 
            "dashboard" => $dashboard
        ],  "status" => true]);
    }

    public function first(Request $request)
    {
        $pedido = firstPedido::first($request);

        $mercancias = getPedidoMercancia::get($request);

        return response()->json(["msg" => "Pedido listado", "data" => [
            "pedido"     => $pedido,
            "mercancias" => $mercancias
        ],  "status" => true]);
    }

    public function create(Request $request)
    {
        $this->pedidos_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado el pedido",  "status" => true]);
    }

    public function update(Request $request)
    {
        $this->pedidos_validations->updateValidation($request);

        return response()->json(["msg" => "Se ha actualizado el pedido",  "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->pedidos_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado el pedido",  "status" => true]);
    }

}
