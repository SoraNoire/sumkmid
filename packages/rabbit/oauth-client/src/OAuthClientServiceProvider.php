<?php

namespace Rabbit\OAuthClient;

use Illuminate\Support\ServiceProvider;

class OAuthClientServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        // include __DIR__.'/Utils/OAuth.php';

        $this->publishes([
            // __DIR__.'/resources/views' => base_path('resources/views/rabbit/oauth'),
            // __DIR__.'/Resources/assets' => public_path('assets'),
            __DIR__.'/Database' => base_path('database'),

        ]);
        // \App::middleware('\Rabbit\OAuthClient\Middleware\OAuthMiddleware::class');
        $router->aliasMiddleware('OAuthMiddleware', \Rabbit\OAuthClient\Middleware\OAuthMiddleware::class);
        // $router->pushMiddleware('\Rabbit\OAuthClient\Middleware\OAuthMiddleware::class');
        $router->aliasMiddleware('backend', \Rabbit\OAuthClient\Middleware\BackendMiddleware::class);
        // $this->app['router']->middleware('OAuth', \Rabbit\OAuthClient\Middleware\OAuthMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        
        if (is_dir(base_path().'/resources/views/rabbit/oauth')) {
            $this->loadViewsFrom(base_path().'/resources/views/rabbit/oauth', 'oa');
        } else {
            $this->loadViewsFrom(__DIR__.'/Resources/views', 'oa');
        }
    }
}
