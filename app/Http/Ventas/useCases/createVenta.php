<?php

namespace App\Http\Ventas\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Models\VentaMercancia;
use App\Events\SalidaInventario;

class createVenta{

    public static function save($data)
    {
        try {
            DB::beginTransaction();
            
            $mercancias = [];
            $subtotal   = 0;
            $descuento  = 0;
            foreach ($data->mercancias as $mercancia) {
                $mercancia = (object)$mercancia;

                $subtotal_mercancia = $mercancia->precio_unitario * $mercancia->cantidad;
                $subtotal           =+ $subtotal_mercancia;
                $descuento          =+ $mercancia->descuento;

                $mercancias[] = [
                    "venta_id"          => $data->venta_id,
                    "inventario_id"     => $mercancia->inventario_id,
                    "tipo"              => $mercancia->tipo_venta,
                    "cantidad"          => $mercancia->cantidad,
                    "precio_unitario"   => $mercancia->precio_unitario,
                    "descuento"         => $mercancia->descuento,
                    "subtotal"          => $subtotal_mercancia,
                    "total"             => $subtotal_mercancia - $mercancia->descuento,
                ];
            }

            VentaMercancia::save((object)[
                "inserts"   => true,
                "data"      => $mercancias
            ]);  

            $total = $subtotal - $descuento;
            Venta::save((object)[
                "data" => [
                    "venta_id"  => $data->venta_id,
                    "fecha"     => $data->fecha,
                    "descuento" => $descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $total,
                    "comentario"    => $data->comentario,
                ]
            ]);
       
            event(new SalidaInventario($mercancias));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al registrar la venta",
                404,
                $e
            );
        }
    }
}