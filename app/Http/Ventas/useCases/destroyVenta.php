<?php

namespace App\Http\Ventas\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Models\VentaMercancia;
use App\Events\AgregarInventario;
use App\Http\VentasMercancias\useCases\getVentaMercancia;

class destroyVenta{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            $date = date('Y-m-d H:i:s');

            $mercancias = getVentaMercancia::get((object)[
                "get_data"  => true,
                "venta_id"  => $data->venta_id
            ]);

            event(new AgregarInventario($mercancias));

            Venta::update(
                [["venta_id", $data->venta_id]],
                [                    
                    "deleted_at"    => $date
                ]
            );

            VentaMercancia::update(
                [["venta_id", $data->venta_id]],
                [                    
                    "deleted_at"    => $date
                ]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al eliminar la venta",
                404,
                $e
            );
        }
    }
}