<?php

namespace Rabbit\OAuthClient\Middleware;

use Closure;

class OAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    function __construct()
    {
        app()->instance('OAuth', new \Rabbit\OAuthClient\Utils\OAuth);
    }

    public function handle($request, Closure $next)
    {
        // dd(app()->OAuth);
        return $next($request);
    }
}