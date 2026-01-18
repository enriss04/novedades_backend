<?php

namespace App\Http\PedidosMercancias\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\PedidoMercancia;
use App\Events\SalidaInventario;
use App\Http\Pedidos\useCases\updatePedidoByMercancias;

class destroyPedidoMercancia{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();
                        \Log::info('$data', [$data]);

            PedidoMercancia::update(
                ["pedido_mercancia_id" => $data->pedido_mercancia_id],
                [                    
                    "deleted_at"    => date('Y-m-d H:i:s')
                ]
            );  

            updatePedidoByMercancias::update((object)[
                "pedido_id" => $data->pedido_id,
                "abono"     => $data->abono,                
            ]);

            event(new SalidaInventario([[
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