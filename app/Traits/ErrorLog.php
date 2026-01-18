<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

trait ErrorLog
{
    /**
     * Reporta la excepción, desenrollándola para encontrar la causa raíz
     * e incluyendo el contexto de la petición.
     *
     * @param \Throwable $exception
     * @return void
    */
    
    public function reportLog(\Throwable $exception): void
    {
        $originalException = $exception;

        // 1. Desenvolver (Unwrap) la excepción para la causa raíz
        while ($previous = $originalException->getPrevious()) {
            $originalException = $previous;
        }
        
        // 2. Información del Error Original
        $exceptionClass = get_class($originalException);
        $errorMessage = $originalException->getMessage();
        
        // 3. Crear el Array de Contexto
        $context = [
            'class'       => $exceptionClass,
            'file'        => $originalException->getFile(),
            'line'        => $originalException->getLine(),
            'url'         => Request::fullUrl(),
            'method'      => Request::method(),
            'user'        => auth()->check() ? auth()->user()->getAuthIdentifier() : 'Guest',
        ];

        $logMessage = "[{$exceptionClass}] {$errorMessage}";

        Log::error($logMessage, $context);
    }
}