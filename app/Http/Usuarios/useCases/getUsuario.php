<?php

namespace App\Http\Usuarios\useCases;

use App\Exceptions\CustomError;

use App\Models\Usuario;

class getUsuario
{

    public static function get($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
            ];

            $data = Usuario::get($params);
           
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
