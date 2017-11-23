<?php

namespace App\Http\Middleware;

use Closure;

class MentorMiddleware
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

        if ( ! 'mentor' == app()->SSO->Auth()->role )
        {
            return redirect("/")->send();
        }

        return $next($request);
    }
}