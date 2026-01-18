<?php

namespace App\Http\Auth\useCases;

use App\Exceptions\CustomError;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Persona;

class loginAccount{

    public static function login($data)
    {
        try {

            $token = JWTAuth::fromUser($data);// Crear un nuevo token
            
            return [
                "token" => $token,
                "user"  => [
                    "cuenta_id" => $data->cuenta_id,
                    "persona_id"    => $data->persona_id,
                    "status"    => $data->status,
                    "cuenta"    => $data->nombre,
                    "telefono"  => $data->persona->telefono,
                    "correo"    => $data->persona->correo,
                    "nombre_completo" => $data->persona->nombre_completo,
                ]
            ];
            
        } catch (\Exception $e) {
            throw new CustomError(
                "Ocurrio un error al iniciar sesión",
                404,
                $e
            );
        }
    }
}