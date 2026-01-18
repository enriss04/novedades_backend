<?php

namespace App\Http\Proveedores\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Persona;
use App\Models\Proveedor;

class updateProveedor{

    public static function update($data)
    {
        try {
            DB::beginTransaction();          

            $persona    = Persona::where('persona_id', $data->persona_id)->first();        

            $persona->update([
                "nombre"            => $data->nombre,
                "primer_apellido"   => $data->primer_apellido,
                "segundo_apellido"  => $data->segundo_apellido,
                "nombre_completo"   => $data->nombre . " " . $data->primer_apellido . " " . $data->segundo_apellido,
                "correo"            => $data->correo,
                "telefono"          => $data->telefono,
            ]);  

            Proveedor::update(
                [["proveedor_id", $data->proveedor_id]],
                [                    
                    "nombre"    => $data->nombre_empresa,  
                ]
            );  

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al actualizar el proveedor",
                404,
                $e
            );
        }
    }
   
}