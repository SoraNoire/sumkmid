<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action
        if (!app()->SSO->Auth())
        {
        	$next = url()->current();
            $appid = env('MD_APP_ID');
            return redirect(route('ssologin')."?appid=$appid&next=$next")->send();
        }

        if (! in_array(app()->SSO->Auth()->role, ['admin']) )
        {
            return redirect("/")->send();
        }

        return $next($request);
    }
}