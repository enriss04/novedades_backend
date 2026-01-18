<?php

namespace App\Http\Proveedores\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;

use App\Models\Persona;
use App\Models\Proveedor;

class createProveedor{

    public static function save($data)
    {
        try {
            DB::beginTransaction();                 

            $persona = new Persona();
            $persona->nombre    = $data->nombre;
            $persona->primer_apellido   = $data->primer_apellido;
            $persona->segundo_apellido  = $data->segundo_apellido;
            $persona->nombre_completo   = "$data->nombre $data->primer_apellido $data->segundo_apellido";
            $persona->correo    = $data->correo;
            $persona->telefono  = $data->telefono;
            $persona->save();

            Proveedor::save((object)[
                "data" => [
                    "nombre"                => $data->nombre_empresa,
                    "encargado_persona_id"  => $persona->persona_id,
                ]
            ]);   

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al registrar al proveedor",
                404,
                $e
            );
        }
    }
}