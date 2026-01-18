<?php

namespace App\Http\Pedidos\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Pedido;
use App\Models\PedidoMercancia;
use App\Events\SalidaInventario;
use App\Http\PedidosMercancias\useCases\getPedidoMercancia;

class destroyPedido{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            $date = date('Y-m-d H:i:s');

            $mercancias = getPedidoMercancia::get((object)[
                "get_data"  => true,
                "pedido_id" => $data->pedido_id
            ]);

            event(new SalidaInventario($mercancias));

            Pedido::update(
                ["pedido_id" => $data->pedido_id],
                [                    
                    "deleted_at"    => $date
                ]
            );

            PedidoMercancia::update(
                ["pedido_id" => $data->pedido_id],
                [                    
                    "deleted_at"    => $date
                ]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al eliminar el pedido",
                404,
                $e
            );
        }
    }
}