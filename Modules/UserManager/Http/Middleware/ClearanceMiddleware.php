<?php

namespace Modules\UserManager\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next) {        
        if (Auth::user()->hasPermissionTo('administration')) //If user has this //permission
        {
            return $next($request);
        }

        
        /**
         * grant mentors edit / update themselves
         **/
        
        if ($request->getPathInfo() == ("/admin/mentors/m/".Auth::id().'/edit')
            || $request->getPathInfo() == ("/admin/mentors/m/".Auth::id()) )
         {
            if (!Auth::user()->hasPermissionTo('moderation'))
            {
                return redirect( route('401') );
            } 
            else
            {
                return $next($request);
            }
        }


        if ($request->getPathInfo() === ("/admin/mentors/m/".Auth::id().'/edit'))//If user is creating a post
         {
          //  dd($request->getPathInfo());
            if (!Auth::user()->hasPermissionTo('moderation'))
            {
                return redirect( route('401') );
            } 
            else
            {
                return $next($request);
            }
        }


        if ($request->is('posts/create'))//If user is creating a post
         {
            if (!Auth::user()->hasPermissionTo('create articles'))
            {
                return redirect( route('401') );
            } 
            else
            {
                return $next($request);
            }
        }

        if ($request->is('posts/*/edit')) //If user is editing a post
        {
            if (!Auth::user()->hasPermissionTo('edit articles'))
            {
                return redirect( route('401') );
            }
            else
            {
                return $next($request);
            }
        }

        if ($request->isMethod('Delete')) //If user is deleting a post
         {
            if (!Auth::user()->hasPermissionTo('delete articles'))
            {
                return redirect( route('401') );
            } 
            else 
            {
                return $next($request);
            }
        }

        //return $next($request);
        return redirect( route('401') );
    }
}