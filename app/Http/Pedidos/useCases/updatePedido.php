<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Pedido;
use App\Http\PedidosMercancias\useCases\getPedidoMercancia;

class updatePedido{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            Pedido::update(
                ["pedido_id" => $data->pedido_id],
                [      
                    "proveedor_id"  => $data->proveedor_id,
                    "fecha"         => $data->fecha,
                    "url_recibo"    => $data->url_recibo,
                    "url_comprobante_pago"  => $data->url_comprobante_pago,
                ]
            );  

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al actualizar el pedido",
                404,
                $e
            );
        }
    }
   
}