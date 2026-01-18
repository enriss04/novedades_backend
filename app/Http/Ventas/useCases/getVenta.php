<?php

namespace App\Http\Ventas\useCases;

use App\Exceptions\CustomError;

use App\Models\Venta;

class getVenta
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
            ];

            $data = Venta::get($params);
           
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
