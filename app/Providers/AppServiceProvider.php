<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Events\RequestHandled;

class AppServiceProvider extends ServiceProvider
{

    public function boot(): void // Auditorías
    {        
        \Event::listen(RequestHandled::class, function (RequestHandled $event) {
            
            // 1. Verificar si la respuesta fue exitosa (código HTTP)
            if ($event->response->isSuccessful() && $event->request->is('api/*')) {
                
                $responseContent = $event->response->getContent();
                $responseData = ($responseContent !== '') ? json_decode($responseContent, true) : [];
                
                // Opcional: Solo loguear si tu payload JSON indica 'status: true'
                if (!isset($responseData['status']) || $responseData['status'] === true) {
                    
                    $route  = $event->request->route();
                    $method = 'N/A';
                    $controller = 'N/A';

                    if ($route) {
                        $action = $route->getAction('uses');
                        // Simplificación para obtener Controller@Method
                        if (is_string($action) && str_contains($action, '@')) {
                             [$controller, $method] = explode('@', $action);
                        } else {
                            $controller = is_string($action) ? $action : 'Closure';
                            $method     = 'N/A';
                        }
                    }                                      

                    // 2. Loguear la petición exitosa
                    Log::channel('successful_requests')->info('Petición exitosa:', [
                        'controller'    => $controller,
                        'action'        => $method,
                        'url'           => $event->request->fullUrl(),
                        'http_method'   => $event->request->method(),
                        'payload'       => $event->request->all(),
                        'response_data' => $responseData,
                        'status_code'   => $event->response->getStatusCode(),
                        'ip_address'    => $event->request->ip()
                    ]);
                }
            }

        });
    }
}