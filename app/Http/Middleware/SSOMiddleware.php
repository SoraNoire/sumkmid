<?php

namespace App\Http\Middleware;

use Closure;

class SSOMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action
    	app()->instance('SSO', new \App\Helpers\SSOHelper);
    	
        return $next($request);
    }
}