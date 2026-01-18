<?php

namespace App\Http\Auth\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Cuenta;
use App\Models\Persona;

class updateAccount{

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
            
            $cuenta    = Cuenta::where('cuenta_id', $data->cuenta_id)->first();
            $cuenta->update([
                "status" => $data->cuenta_status,
            ]);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();
            throw new CustomError(
                "Ocurrio un error al actualizar la cuenta",
                404,
                $e
            );

        }
    }
}