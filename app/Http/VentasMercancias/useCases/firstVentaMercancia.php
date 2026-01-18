<?php

namespace App\Http\VentasMercancias\useCases;

use App\Exceptions\CustomError;

use App\Models\VentaMercancia;

class firstVentaMercancia{

    public static function first($request)
    {
        try {

            $params = (object)[
                'get_data'  => $request->get_data ?? false,
                'venta_id'  => $request->venta_id ?? null,
                'inventario_id' => $request->inventario_id ?? null,
            ];
    
            $data = VentaMercancia::first($params);
            
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