<?php

namespace App\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {        
        try {            
            // Obtiene el token desde la cookie
            $token = $request->cookie('token');

            if(is_null($token)){
                return response()->json([
                    "status"    => false,
                    "info"      => [],
                    "msg"       => "La sesión ha caducado",
                    "errorType"     => "cookieExpired"
                ], 401);
            }

            // Autentica al usuario con el token de la cookie
            $user = JWTAuth::setToken($token)->authenticate()->load('persona');

            // Asocia al usuario con el request, es decir, los datos del usuario los pongo 
            // en el request para que sean accedidos a lo largo de la ejecucion del codigo
            $request->setUserResolver(fn() => $user);
            
            return $next($request);
        } catch (\Throwable $th) {
            if ($th instanceof TokenExpiredException) {
                throw $th;        
            } else {
                if (!empty($token)) {
                    JWTAuth::setToken($token)->invalidate();
                }
                
                throw $th;        
            } 
        }
    }
}