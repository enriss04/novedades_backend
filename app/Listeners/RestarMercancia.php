<?php

namespace App\Listeners;

use App\Events\SalidaInventario;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Inventario;
use App\Http\Inventario\useCases\firstInventario;

class RestarMercancia
{

    public function handle(SalidaInventario $event): void
    {
        foreach ($event->inventario as $mercancia){
            $mercancia = (object)$mercancia;

            $inventario = firstInventario::first((object)[
                "get_data"      => true,
                "inventario_id" => $mercancia->inventario_id
            ]);

            if($inventario){
                Inventario::update(
                ["inventario_id" => $inventario->inventario_id],
                [                    
                    "existencia"    => $inventario->existencia - $mercancia->cantidad,  
                    "salida"        => $inventario->salida + $mercancia->cantidad,  
                ]);                
            }
        }
    }

}
