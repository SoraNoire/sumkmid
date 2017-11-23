<?php

namespace App\Providers;

use \App\Helpers\SSOHelper;
use Illuminate\Support\ServiceProvider;

class SSOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SSOHelper::class, function ($app) {
            return new SSOHelper;
        });
    }
}
