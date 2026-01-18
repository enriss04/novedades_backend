<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Inventario;
use App\Events\AgregarInventario;
use App\Http\Inventario\useCases\firstInventario;
 
class SumarMercancia
{

    public function handle(AgregarInventario $event): void
    {

        foreach ($event->inventario as $mercancia){
            $mercancia = (object)$mercancia;

            $inventario = firstInventario::first((object)[
                "get_data"      => true,
                "inventario_id" => $mercancia->inventario_id
            ]);        

            if($inventario){
                
                $existencia = $inventario->existencia + $mercancia->cantidad;

                Inventario::update(
                ["inventario_id" => $inventario->inventario_id],
                [                    
                    "existencia"    => $existencia,
                    "ingreso"       => $existencia,  
                ]);

            }
        }
    }
    
}
