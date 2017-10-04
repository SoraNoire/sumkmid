<?php

namespace Modules\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Shortcode;
use Modules\Blog\Http\Shortcodes\BoldShortcode;
use Modules\Blog\Http\Shortcodes\ItalicShortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Shortcode::register('b', BoldShortcode::class);
        Shortcode::register('i', ItalicShortcode::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
