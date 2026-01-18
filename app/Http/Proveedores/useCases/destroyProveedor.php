<?php

namespace App\Http\Proveedores\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Persona;
use App\Models\Proveedor;

class destroyProveedor{

    public static function destroy($data)
    {
        try {
            DB::beginTransaction();

            $date = date('Y-m-d H:i:s');

            $persona    = Persona::where('persona_id', $data->persona_id)->first();        
            $persona->update([
                "deleted_at"    => $date                
            ]);  

            Proveedor::update(
                ["proveedor_id" => $data->proveedor_id],
                [                    
                    "deleted_at"    => $date
                ]
            );  
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al eliminar el proveedor",
                404,
                $e
            );
        }
    }
}