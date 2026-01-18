<?php

namespace App\Http\Ventas\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;

class updateVenta{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            Venta::update(
                ["venta_id" => $data->venta_id],
                [                    
                    "fecha"         => $data->fecha,
                    "comentario"    => $data->comentario,
                ]
            );  

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al actualizar la venta",
                404,
                $e
            );
        }
    }
   
}