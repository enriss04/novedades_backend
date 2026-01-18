<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Pedido;
use App\Http\PedidosMercancias\useCases\getPedidoMercancia;

class updatePedidoByMercancias{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            $mercancias = getPedidoMercancia::get((object)[
                "get_data"  => true,
                "pedido_id" => $data->pedido_id
            ]);

            $subtotal   = 0;
            $descuento  = 0;
            foreach ($mercancias as $mercancia) {
                $subtotal_mercancia = $mercancia->precio_unitario * $mercancia->cantidad;
                $subtotal           =+ $subtotal_mercancia;
                $descuento          =+ $mercancia->descuento;
            }

            $total = $subtotal - $descuento;
            Pedido::update(
                ["pedido_id" => $data->pedido_id],
                [      
                    "descuento" => $descuento,
                    "subtotal"  => $subtotal,
                    "total"     => $total,
                    "abono"     => $data->abono,
                    "status"    => $data->abono == $total ? 'Pagado' : ($data->abono > 0 ? 'Abonado' : 'Pendiente'),  
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