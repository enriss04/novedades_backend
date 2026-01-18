<?php

namespace App\Http\Inventario\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Inventario;

class createInventario{

    public static function save($data)
    {
        try {
            DB::beginTransaction();                    

            Inventario::save((object)[
                "data" => [
                    "inventario_id"   => $data->inventario_id,
                    "talla"     => $data->talla,
                    "color"     => $data->color,
                    "modelo"    => $data->modelo,
                    "tipo"      => $data->tipo,
                    "existencia"    => $data->existencia,
                    "ingreso"       => $data->ingreso,
                    "salida"        => $data->salida,
                    "devoluciones"  => $data->devoluciones,
                    "url"           => $data->url,
                    "precio_mayoreo"    => $data->precio_mayoreo,
                    "precio_menudeo"    => $data->precio_menudeo,
                ]
            ]);   

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al registrar la mercancia",
                404,
                $e
            );
        }
    }
}