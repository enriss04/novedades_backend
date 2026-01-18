<?php

namespace App\Http\Proveedores\useCases;

use App\Exceptions\CustomError;

use App\Models\Proveedor;

class firstProveedor{

    public static function first($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,                              
            ];
    
            $data = Proveedor::first($params);
            
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