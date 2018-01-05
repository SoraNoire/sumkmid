<?php

namespace Rabbit\OAuthClient\Middleware;

use Rabbit\OAuthClient\Models\Modules;
use Rabbit\OAuthClient\Models\ModulePermissions;
use Route;
use Closure;

class BackendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */



    public function handle($request, Closure $next,$access='')
    {

        // route name as module

        // user outside context

        if ( !isset(app()->OAuth::Auth()->role) || ! in_array( app()->OAuth::Auth()->role, app()->OAuth::$roles) )
        {
            return redirect('/');
        }

        $moduleName = $request->route()->getName();
        // dd($moduleName);
        $moduleName = explode("__", $moduleName);
        $method = false;
        if( isset($moduleName[1]) )
        {
            if( 'save'==$moduleName[1]
                || 'add'==$moduleName[1]
            )
            {
                $method = 'write';
            }
            if( 'update'==$moduleName[1] 
                || 'view'==$moduleName[1] 
            )
            {
                $method = 'edit';
            }
            if( 'delete'==$moduleName[1]
            )
            {
                $method = 'delete';
            }
            if( 'index'==$moduleName[1]
            )
            {
                $method = 'read';
            }
        }
        $moduleName = $moduleName[0];
    
        if (!$method) {
            if( $moduleName != 'panel.dashboard' 
                && $moduleName != 'OA.dashboard' 
                && $moduleName != 'OA.permissions'
                && $moduleName != 'OA.permissions.save.ajax'
                && $moduleName != 'OA.modules'
                && $moduleName != 'OA.module.save'
            )
            {
                return redirect('/');
            }
            if('admin' != app()->OAuth->Auth()->role)
            {
                return redirect(route('panel.dashboard'));   
            }
        }

        if (!$moduleName)
        {
            return redirect('/');
        }

        if ( !app()->OAuth->Auth() )
        {
            return redirect('/');
        }

        $module = Modules::where('name',$moduleName)->first();
        $perms = [];

        if ($module)
        {
            $grants = ModulePermissions::where('module_id',$module->id)
                            ->where('role',app()->OAuth->Auth()->role)
                            ->with('module')
                            ->first();

            if(!$grants || !isset($grants->write))
            {   
                if( $moduleName != 'panel.dashboard' 
                    && $moduleName != 'OA.dashboard' 
                    && $moduleName != 'OA.permissions'
                    && $moduleName != 'OA.permissions.save.ajax'
                    && $moduleName != 'OA.modules'
                    && $moduleName != 'OA.module.save'
                )
                {
                    return redirect('/');
                }
                if('admin' != app()->OAuth->Auth()->role)
                {
                    return redirect(route('panel.dashboard'));   
                }
            }

            // collect permissions
            if($grants && 1==$grants->write){
                $perms[] = 'write';
                app()->OAuth::$can[] = 'write';
            }

            if( $grants && 1==$grants->read){
                $perms[] = 'read';
                app()->OAuth::$can[] = 'read';
            }

            if( $grants && 1==$grants->edit){
                $perms[] = 'edit';
                app()->OAuth::$can[] = 'edit';
            }

            if( $grants && 1==$grants->delete){
                $perms[] = 'delete';
                app()->OAuth::$can[] = 'delete';
            }

        }
        
        
        // if(isset($perms))

        // permission not in home
        if( ! in_array($method, $perms) )
        {

            if( $moduleName != 'panel.dashboard' 
                && $moduleName != 'OA.dashboard' 
                && $moduleName != 'OA.permissions' 
                && $moduleName != 'OA.permissions.save.ajax'
                && $moduleName != 'OA.modules'
                && $moduleName != 'OA.module.save'
            )
            {
                return redirect('/');
            }
            if('admin' != app()->OAuth->Auth()->role)
            {
                return redirect(route('panel.dashboard'));   
            }
        }

        return $next($request);
    }
}