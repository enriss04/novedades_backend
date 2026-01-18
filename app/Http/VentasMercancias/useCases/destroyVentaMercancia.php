<?php

namespace App\Http\VentasMercancias\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\VentaMercancia;
use App\Events\AgregarInventario;
use App\Http\Ventas\useCases\updateVentaByMercancias;

class destroyVentaMercancia{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            VentaMercancia::update(
                [["venta_mercancia_id", $data->venta_mercancia_id]],
                [                    
                    "deleted_at"    => date('Y-m-d H:i:s')
                ]
            );  
            
            updateVentaByMercancias::update((object)[
                "venta_id" => $data->venta_id,
            ]);

            event(new AgregarInventario([[
                "inventario_id" => $data->inventario_id,
                "cantidad"      => $data->cantidad
            ]]));

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