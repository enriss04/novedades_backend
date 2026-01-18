<?php

namespace App\Http\Inventario\useCases;

use App\Exceptions\CustomError;

use App\Models\Inventario;

class firstInventario{

    public static function first($request)
    {
        try {

            $params = (object)[
                'get_data'      => $request->get_data ?? false,
                'inventario_id' => $request->inventario_id ?? null,
            ];
    
            $data = Inventario::first($params);
            
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