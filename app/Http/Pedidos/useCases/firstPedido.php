<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;

use App\Models\Pedido;

class firstPedido{

    public static function first($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
                'pedido_id' => $request->pedido_id ?? null,
            ];
    
            $data = Pedido::first($params);
            
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