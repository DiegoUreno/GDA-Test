<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogIPMiddleware
{
    public function handle($request, Closure $next)
    {

        $ip = $request->ip();


        Log::info("IP del cliente: $ip");


        return $next($request);
    }
}

