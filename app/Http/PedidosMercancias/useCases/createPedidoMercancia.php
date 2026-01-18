<?php

namespace App\Http\PedidosMercancias\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\PedidoMercancia;
use App\Events\AgregarInventario;
use App\Http\Pedidos\useCases\updatePedidoByMercancias;

class createPedidoMercancia{

    public static function save($data)
    {
        try {
            DB::beginTransaction();
            
            $subtotal = ($data->precio_unitario * $data->cantidad);
            $id = PedidoMercancia::save((object)[
                "data" => [
                    "inventario_id"     => $data->inventario_id,
                    "pedido_id"         => $data->pedido_id,
                    "precio_unitario"   => $data->precio_unitario,
                    "cantidad"  => $data->cantidad,
                    "descuento" => $data->descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $subtotal - $data->descuento,
                ]
            ]);                            

            updatePedidoByMercancias::update((object)[
                "pedido_id" => $data->pedido_id,
                "abono"     => $data->abono,                
            ]);

            event(new AgregarInventario([[
                "inventario_id" => $data->inventario_id,
                "cantidad"      => $data->cantidad
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