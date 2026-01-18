<?php

namespace App\Traits;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\CustomError;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait Error
{
    public function render($exception)
    {
        $responseData = $this->prepareException($exception);
        $payload = Arr::except($responseData, 'statusCode');
        $statusCode = $responseData['statusCode'];

        return response()->json($payload, $statusCode);
    }

    private function prepareException(Throwable $exception): array
    {
        $responseData['info']       = [];
        $responseData['errorType']  = null;

        $message = $exception->getMessage();

        if ($exception instanceof NotFoundHttpException) {
            $responseData['msg']        = "Recurso no encontrado";
            $responseData["statusCode"] = 404;
            $responseData["errorType"]  = "NotFoundHttpException";
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $responseData['msg']        = $message;
            $responseData['statusCode'] = 405;
            $responseData["errorType"]  = "MethodNotAllowedHttpException";
        } elseif ($exception instanceof ModelNotFoundException) {
            $responseData['msg']        = "No se encontró el recurso: {$this->modelNotFoundMessage($exception)}.";
            $responseData['statusCode'] = 404;
            $responseData["errorType"]  = "ModelNotFoundException";
        } elseif ($exception instanceof TokenExpiredException) {
            $responseData['msg']        = "El token ha expirado.";
            $responseData['statusCode'] = 401;
            $responseData["errorType"]  = "TokenExpiredException";
        } elseif ($exception instanceof TokenInvalidException) {
            $responseData['msg']        = "El token es inválido.";
            $responseData['statusCode'] = 401;
            $responseData["errorType"]  = "TokenInvalidException";
        } elseif ($exception instanceof JWTException) {
            $responseData['msg']        = "El token no fue encontrado.";
            $responseData['statusCode'] = 401;
            $responseData["errorType"]  = "JWTException";
        } elseif ($exception instanceof ValidationException) {
            $responseData['msg']        = $message;
            $responseData['statusCode'] = 422;
            $responseData["errorType"]  = "ValidationException";
        }  elseif ($exception instanceof CustomError) {
            $responseData['msg']        = $message;
            $responseData['statusCode'] = $exception->getCode();
            $responseData['info']       = $exception->getData() ?: [];
            $responseData["errorType"]  = "CustomError";
        }else {
            $responseData['msg']    = "Hubo un error en el servidor, intentelo más tarde.";
            $responseData['statusCode'] = 500;
        }

        return $responseData;
    }

    private function modelNotFoundMessage(ModelNotFoundException $exception): string
    {
        if (!is_null($exception->getModel())) {
            return Str::lower(ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($exception->getModel()))));
        }
        
        return 'modelo';
    }
}