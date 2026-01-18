<?php

namespace App\Http\VentasMercancias\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\VentaMercancia;
use App\Events\SalidaInventario;
use App\Http\Ventas\useCases\updateVentaByMercancias;

class createVentaMercancia{

    public static function save($data)
    {
        try {
            DB::beginTransaction();
            
            $subtotal = ($data->precio_unitario * $data->cantidad);
            $id = VentaMercancia::save((object)[
                "data" => [
                    "inventario_id" => $data->inventario_id,
                    "venta_id"      => $data->venta_id,
                    "tipo"          => $data->tipo_venta,
                    "precio_unitario"   => $data->precio_unitario,
                    "cantidad"  => $data->cantidad,
                    "descuento" => $data->descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $subtotal - $data->descuento,
                ]
            ]);                            

            updateVentaByMercancias::update((object)[
                "venta_id" => $data->venta_id,
            ]);

            event(new SalidaInventario([[
                "inventario_id" => $data->inventario_id,
                "cantidad"    => $data->cantidad
            ]]));

            DB::commit();

            return $id;
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