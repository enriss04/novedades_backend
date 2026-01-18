<?php

namespace App\Http\Auth;

use Illuminate\Http\Request;
use App\Http\Auth\AuthValidations;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller{

    protected $authValidation;

    public function __construct(AuthValidations $authValidation)
    {
        $this->authValidation = $authValidation;
    }

    public function login(Request $request)
    {        
        $data = $this->authValidation->loginValidation($request);
        
        $cookie = new Cookie(
            'token',
            $data['token'],
            now()->addMinutes(480),
            '/',
            null,
            true, // Secure
            true, // HttpOnly
            false, // Raw
            'None' // SameSite
        );

        return response()
            ->json(["msg" => "Hola, bienvenida de nuevo", "data" => $data['user'], "status" => true])
            ->withCookie($cookie);
    }

    public function create(Request $request)
    {        
        $this->authValidation->createValidation($request);  

        return response()
        ->json(["msg" => "La cuenta ha sido creada", "status" => true]);
    }

    public function reconnect(Request $request)
    {        
        $this->authValidation->reconnectValidation($request);  

        return response()
        ->json(["msg" => "Autenticación correcta", "status" => true]);
    }

    public function logout(Request $request)
    {        
        return response()
            ->json(["msg" => "Su sesión ha sido cerrada",  "status" => true])
            ->cookie(cookie()->forget('token'));
    }

    public function updatePassword(Request $request)
    {        
        $this->authValidation->updatePasswordValidation($request);  

        return response()
            ->json(["msg" => "La contraseña se ha actualizado", "status" => true]);
    }

    public function update(Request $request)
    {        
        $this->authValidation->updateValidation($request);  

        return response()
            ->json(["msg" => "Su información se ha actualizado", "status" => true]);
    }
}