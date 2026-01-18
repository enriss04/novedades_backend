<?php

namespace App\Http\Inventario\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Inventario;

class destroyInventario{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            Inventario::update(
                ["inventario_id" => $data->inventario_id],
                [                    
                    "deleted_at"    => date('Y-m-d H:i:s')
                ]
            );  
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al eliminar la mercancia",
                404,
                $e
            );
        }
    }
}