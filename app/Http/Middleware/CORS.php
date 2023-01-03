<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin:', '*');
        header('Access-Control-Allow-Methods:', '*');
        header('Access-Control-Allow-Methods:', 'GET');
        header('Access-Control-Allow-Methods:', 'POST');
        header('Access-Control-Allow-Methods:', 'PUT');
        header('Access-Control-Allow-Methods:', 'DELETE');
        header('Access-Control-Allow-Headers : Content-type, X-Auth-Token, Authorization, Origin');
        return $next($request);
    }
}
