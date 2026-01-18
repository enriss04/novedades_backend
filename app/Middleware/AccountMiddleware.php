<?php

namespace App\Middleware;

use Closure;
use App\Http\Auth\useCases\activeAccount;

class AccountMiddleware
{
    public function handle($request, Closure $next){
        activeAccount::validate('cuenta_id', $request->user()->cuenta_id);
        
        return $next($request);
    }
}