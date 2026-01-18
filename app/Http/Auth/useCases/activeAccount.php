<?php

namespace App\Http\Auth\useCases;

use App\Exceptions\CustomError;

use App\Models\Cuenta;

class activeAccount{

    public static function validate($attribute, $params)
    {
        $access = Cuenta::where($attribute, $params)->with('persona')->first();
        $status = $access['status'] ?? null;

        if(is_null($status)){
            throw new CustomError(
                "La cuenta no existe, para acceder al sistema contacte con el administrador",
                401            
            );  
        }

        if($status !== 'Activo'){            
            throw new CustomError(
                "No tiene acceso al recurso, su cuenta está $status",
                401            
            );                    
        }

        return $access;
    }
}