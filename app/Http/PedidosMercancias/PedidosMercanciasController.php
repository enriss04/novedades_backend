<?php

namespace App\Http\PedidosMercancias;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\PedidosMercancias\useCases\getPedidoMercancia;
use App\Http\PedidosMercancias\useCases\firstPedidoMercancia;
use App\Http\PedidosMercancias\PedidosMercanciasValidations;

class PedidosMercanciasController extends Controller
{

    protected $pedidos_mercancias_validations;

    public function __construct(PedidosMercanciasValidations $pedidos_mercancias_validations)
    {
        $this->pedidos_mercancias_validations = $pedidos_mercancias_validations;
    }

    public function get(Request $request)
    {
        $data = getPedidoMercancia::get($request);

        return response()->json(["msg" => "Pedidos mercancias listadas", "data" => $data,  "status" => true]);
    }

    public function first(Request $request)
    {
        $data = firstPedidoMercancia::first($request);

        return response()->json(["msg" => "Pedido mercancia listada", "data" => $data,  "status" => true]);
    }

    public function create(Request $request)
    {
        $id = $this->pedidos_mercancias_validations->createValidation($request);

        return response()->json(["msg" => "Se ha guardado el pedido de la mercancia", "data" => $id, "status" => true]);
    }

    public function destroy(Request $request)
    {
        $this->pedidos_mercancias_validations->destroyValidation($request);

        return response()->json(["msg" => "Se ha eliminado el pedido de la mercancia",  "status" => true]);
    }

}
