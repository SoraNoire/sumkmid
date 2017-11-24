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
            return redirect("http://authdev.mdirect.id/client_login?appid=$appid&next=$next")->send();
        }

        if (! in_array(app()->SSO->Auth()->role, ['admin','superadmin']) )
        {
            return redirect("/")->send();
        }

        return $next($request);
    }
}