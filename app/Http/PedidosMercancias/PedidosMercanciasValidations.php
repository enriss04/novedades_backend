<?php

namespace App\Http\PedidosMercancias;

use App\Services\SaveFile;
use Illuminate\Support\Str;

use App\Http\PedidosMercancias\useCases\createPedidoMercancia;
use App\Http\PedidosMercancias\useCases\updatePedidoMercancia;
use App\Http\PedidosMercancias\useCases\destroyPedidoMercancia;

class PedidosMercanciasValidations {

    public function createValidation($request) 
    {

        $validatedData = $request->validate(
        [
            'inventario_id' => 'required|string',
            'pedido_id'     => 'required|string',
            'abono'         => 'required|numeric',
            'precio_unitario'   => 'required|numeric',
            'cantidad'          => 'required|integer',
            'descuento'         => 'required|numeric',
        ]);
        $data = (object)$validatedData;        

        return createPedidoMercancia::save($data);
    }

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'inventario_id'     => 'required|string',
                'pedido_mercancia_id'   => 'required|integer',
                'pedido_id'         => 'required|string',
                'abono'             => 'required|numeric',
                'cantidad'          => 'required|integer',
            ]
        );

        destroyPedidoMercancia::destroy($request);
    }
}