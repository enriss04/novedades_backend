<?php

namespace App\Http\PedidosMercancias\useCases;

use App\Exceptions\CustomError;

use App\Models\PedidoMercancia;

class firstPedidoMercancia{

    public static function first($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
                'pedido_id' => $request->pedido_id ?? null,
                'inventario_id' => $request->inventario_id ?? null,
            ];
    
            $data = PedidoMercancia::first($params);
            
            return $data;
            
        } catch (\Exception $e) {

            throw new CustomError(
                "Ocurrio un error al obtener la información",
                404,
                $e
            );

        }
    }
}