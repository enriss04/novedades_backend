<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Pedido;
use App\Models\PedidoMercancia;
use App\Events\AgregarInventario;

class createPedido{

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
                    "pedido_id"         => $data->pedido_id,
                    "inventario_id"     => $mercancia->inventario_id,
                    "precio_unitario"   => $mercancia->precio_unitario,
                    "cantidad"          => $mercancia->cantidad,
                    "descuento"         => $mercancia->descuento,
                    "subtotal"          => $subtotal_mercancia,
                    "total"             => $subtotal_mercancia - $mercancia->descuento,
                ];
            }

            PedidoMercancia::save((object)[
                "inserts"   => true,
                "data"      => $mercancias
            ]);  

            $total = $subtotal - $descuento;
            Pedido::save((object)[
                "data" => [
                    "pedido_id"     => $data->pedido_id,
                    "proveedor_id"  => $data->proveedor_id,
                    "fecha"         => $data->fecha,
                    "descuento" => $descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $total,
                    "abono"     => $data->abono,
                    "status"    => $data->abono == $total ? 'Pagado' : ($data->abono > 0 ? 'Abonado' : 'Pendiente'),
                    "url_recibo"    => $data->url_recibo,
                    "url_comprobante_pago"  => $data->url_comprobante_pago,
                ]
            ]);

            event(new AgregarInventario($mercancias));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al registrar el pedido",
                404,
                $e
            );
        }
    }
}