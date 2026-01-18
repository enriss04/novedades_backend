<?php

namespace App\Http\Usuarios;

use Illuminate\Support\Str;

use App\Http\Usuarios\useCases\destroyUsuario;

class UsuariosValidations {

    public function destroyValidation($request) 
    {
        $request->validate(
            [
                'cuenta_id'     => 'required',
                'persona_id'    => 'required',
            ]
        );

        destroyUsuario::destroy($request);
    }

}