<?php

namespace App\Http\Auth\useCases;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Cuenta;
use App\Models\Persona;

class createAccount{

    public static function create($data)
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

            $cuenta         = new Cuenta();
            $cuenta->persona_id = $persona->persona_id;
            $cuenta->nombre = $data->cuenta;
            $cuenta->status = $data->cuenta_status;
            $cuenta->clave  = Hash::make($data->clave);
            $cuenta->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomError(
                "Ocurrio un error al registrar la cuenta",
                404,
                $e
            );
        }
    }
}