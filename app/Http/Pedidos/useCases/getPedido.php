<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;

use App\Models\Pedido;

class getPedido
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
            ];

            $data = Pedido::get($params);
           
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
