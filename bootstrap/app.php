<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Exceptions\ApiErrorLog;     // Esta clase me permite crear un log para imprimir el mensaje de error que haya ocurrido
use App\Exceptions\ApiErrorHandler; // Esta clase me permite controlar los errores en el sistema y responder al cliente evitando que laravel falle 
                                    // y deje de funcionar

use App\Middleware\JwtMiddleware;   //comprueba si el token y la cookie que envia el cliente es valida
use App\Middleware\AccountMiddleware;   //comprueba si la cuenta esta activa o es valida

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void { // Aqui se definen los middleware a utilizar        
        $middleware->appendToGroup('request-validation', [
            JwtMiddleware::class,
            AccountMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $exception) {
            return false;
        });

        $exceptions->render(function (\Throwable $exception, $request) {
            if ($request->is('api/*')) {
                app(ApiErrorLog::class)->reportLog($exception);
                
                return app(ApiErrorHandler::class)->render($exception);
            }

            return response()->json([
                "msg" => 'Resource not found'
            ], 500);
        });
    })->create();
