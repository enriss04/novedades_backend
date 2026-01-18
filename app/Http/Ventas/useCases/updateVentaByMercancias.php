<?php

namespace App\Http\Ventas\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Http\VentasMercancias\useCases\getVentaMercancia;

class updateVentaByMercancias{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            $mercancias = getVentaMercancia::get((object)[
                "get_data"  => true,
                "venta_id" => $data->venta_id
            ]);

            $subtotal   = 0;
            $descuento  = 0;
            foreach ($mercancias as $mercancia) {
                $subtotal_mercancia = $mercancia->precio_unitario * $mercancia->cantidad;
                $subtotal           =+ $subtotal_mercancia;
                $descuento          =+ $mercancia->descuento;
            }

            $total = $subtotal - $descuento;
            Venta::update(
                ["venta_id" => $data->venta_id],
                [      
                    "descuento" => $descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $total,
                ]
            );  

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al actualizar los datos del pedido",
                404,
                $e
            );
        }
    }
   
}