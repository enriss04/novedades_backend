<?php

namespace App\Http\Inventario\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Inventario;

class updateInventario{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            Inventario::update(
                [["inventario_id", $data->inventario_id]],
                [                    
                    "talla"     => $data->talla,
                    "color"     => $data->color,
                    "modelo"    => $data->modelo,
                    "tipo"      => $data->tipo,              
                    "url"           => $data->url,
                    "existencia"    => $data->existencia,
                    "ingreso"       => $data->ingreso,
                    "salida"        => $data->salida,
                    "devoluciones"  => $data->devoluciones,
                    "precio_mayoreo"    => $data->precio_mayoreo,
                    "precio_menudeo"    => $data->precio_menudeo,
                ]
            );  

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al actualizar la mercancia",
                404,
                $e
            );
        }
    }
   
}