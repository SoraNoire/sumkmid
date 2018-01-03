<?php

namespace Rabbit\SahabatUser;

use Illuminate\Support\ServiceProvider;

class SahabatUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {

        $this->publishes([
            __DIR__.'/Database' => base_path('database'),

        ]);
        // $router->aliasMiddleware('SahabatUserMiddleware', \Rabbit\SahabatUser\Middleware\SahabatUserMiddleware::class);
        $router->aliasMiddleware('shbbackend', \Rabbit\SahabatUser\Middleware\BackendMiddleware::class);
        $router->aliasMiddleware('shbuser', \Rabbit\SahabatUser\Middleware\SahabatUserMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        
        if (is_dir(base_path().'/resources/views/rabbit/user')) {
            $this->loadViewsFrom(base_path().'/resources/views/rabbit/user', 'shb');
        } else {
            $this->loadViewsFrom(__DIR__.'/Resources/views', 'shb');
        }
    }
}
