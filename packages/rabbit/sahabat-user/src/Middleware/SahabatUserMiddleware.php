<?php

namespace Rabbit\SahabatUser\Middleware;

use Closure;
use Rabbit\SahabatUser\Models\UserMeta;

class SahabatUserMiddleware
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
        // app()->instance('OAuth', new \Rabbit\OAuthClient\Utils\OAuth);
        if(app()->OAuth::Auth())
        {
            $userMeta = UserMeta::where('user_id',app()->OAuth::Auth()->id)->get();
            $userData = [];
            $user = app()->OAuth::$Auth;
            $userMeta->map(
                function($meta) use(&$userData,&$user){
                    if( 'type_user' == $meta->meta_key )
                    {
                        $user->role = $meta->meta_value;
                    }
                    else{
                        // $user->{$meta->meta_key} = $meta->meta_value;
                        $userData[$meta->meta_key] = $meta->meta_value;
                    }
                }
            );
            app()->OAuth::$Auth->data = (object)$userData;
            unset($user);
        }
    }

    public function handle($request, Closure $next)
    {
        if(app()->OAuth::Auth())
        {
            if( in_array(app()->OAuth::Auth()->role, ['umkm','perorangan','visitor' , 'Visitor']) )
            {
                if(app()->OAuth::Auth()->data && !isset(app()->OAuth::Auth()->data->tos_terima) || 1!= app()->OAuth::Auth()->data->tos_terima)
                {
                    return redirect( route('SHB.complete_data',1) )->send();
                }
            }
        }

        return $next($request);
    }
}