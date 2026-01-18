<?php

namespace App\Http\Inventario\useCases;

use App\Exceptions\CustomError;

use App\Models\Inventario;

class getInventario
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
            ];

            $data = Inventario::get($params);
           
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
