<?php

namespace App\Http\Auth;

use App\Exceptions\CustomError;
use Illuminate\Support\Facades\Hash;

use App\Models\Cuenta;
use App\Http\Auth\useCases\loginAccount;
use App\Http\Auth\useCases\activeAccount;
use App\Http\Auth\useCases\createAccount;
use App\Http\Auth\useCases\updateAccount;
use App\Http\Auth\useCases\updatePassword;

class AuthValidations {

    public function loginValidation($request) 
    {
        $validatedData = $request->validate(
        [
            'cuenta'    => 'required',
            'clave'     => 'required',
        ],
        [
            'cuenta.required'   => 'El campo cuenta es obligatorio.',
            'clave.required'    => 'El campo clave es obligatorio.',            
        ]);
        $data = (object)$validatedData;

        $access = activeAccount::validate('nombre', $data->cuenta);

        if (!Hash::check($data->clave, $access->clave)) {
            throw new CustomError(
                "La contraseña no es correcta",
                404                
            );
        }
        
        return loginAccount::login($access);
    }

    public function reconnectValidation($request) 
    {
        $validatedData = $request->validate(
        [
            'clave'     => 'required',
            'cuenta'    => 'required',
        ],
        [
            'clave.required'    => 'El campo clave es obligatorio.',
            'cuenta.required'   => 'El campo cuenta es obligatorio.',            
        ]
        );
        $data = (object)$validatedData;

        $access = activeAccount::validate('nombre', $data->cuenta);
    
        if (!Hash::check($data->clave, $access->clave)) {
            throw new CustomError(
                "La contraseña no es correcta.",
                404                
            );
        }
        
    }

    public function createValidation($request) 
    {
        $validatedData = $request->validate(
        [
            'cuenta'    => 'required',
            'clave'     => 'required',
            'nombre'    => 'required|string',
            'primer_apellido'   => 'required|string',
            'correo'            => 'required|email',
            'cuenta_status'   => 'required|string',
        ],
        [
            'cuenta.required'   => 'El campo cuenta es obligatorio.',
            'clave.required'    => 'El campo clave es obligatorio.',            
            'nombre.required'   => 'El campo nombre es obligatorio.',            
            'primer_apellido.required'  => 'El campo primer_apellido es obligatorio.',            
            'correo.required'   => 'El campo correo es obligatorio.', 
            'correo.email'      => 'El correo no es valido.',
            'cuenta_status.required'   => 'El campo cuenta_status es obligatorio.',
        ]
        );
        $data = (object)$validatedData;
        $data->segundo_apellido = $request->segundo_apellido ?? '';
        $data->telefono         = $request->telefono ?? null;

        $access = Cuenta::where('nombre', $data->cuenta)->first();
        
        if(!is_null($access)){
            throw new CustomError(
                "Existe una cuenta con el nombre establecido.",
                404                
            );
        }

        createAccount::create($data);
    }

    public function updatePasswordValidation($request) 
    {
        $validatedData = $request->validate(
        [
            'cuenta'            => 'required|string',
            'current_password'  => 'required|string',
            'new_password'      => 'required|string',
        ],
        [
            'cuenta.required'           => 'El campo cuenta es obligatorio.',
            'current_password.required' => 'El campo current_password es obligatorio.',
            'new_password.required'     => 'El campo new_password es obligatorio.',
        ]
        );
        $data = (object)$validatedData;

        $access = activeAccount::validate('nombre', $data->cuenta);
            
        // if (!Hash::check($data->current_password, $access->clave)) {
        //     throw new CustomError(
        //         "La contraseña no es correcta.",
        //         404                
        //     );
        // }        

        updatePassword::update($data);
    }

    public function updateValidation($request) 
    {
        $validatedData = $request->validate(
        [
            'cuenta_id'        => 'required|integer',
            'persona_id'        => 'required|integer',
            'nombre'            => 'required|string',
            'cuenta_status'     => 'required|string',
        ],
        [
            'persona_id.required'   => 'El campo persona_id es obligatorio.',
            'cuenta_id.required'   => 'El campo cuenta_id es obligatorio.',
            'nombre.required'       => 'El campo nombre es obligatorio.',
            'cuenta_status.required'   => 'El campo cuenta_status es obligatorio.',                    
        ]
        );
        $data = (object)$validatedData;        
        $data->primer_apellido = $request->primer_apellido ?? '';
        $data->segundo_apellido = $request->segundo_apellido ?? '';
        $data->telefono = $request->telefono ?? '';
        $data->correo = $request->correo ?? '';

        updateAccount::update($data);
    }
}