<?php

namespace bisual\bisualmail;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class bisualmailServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Route::middlewareGroup('bisualmail', config('bisualmail.middlewares', []));

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bisualmail');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bisualmail');
        $this->registerRoutes();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'namespace' => 'bisual\bisualmail\Http\Controllers',
            'prefix' => config('bisualmail.path'),
            'middleware' => 'bisualmail',
        ];
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bisualmail.php', 'bisualmail');

        // Register the service the package provides.
        $this->app->singleton('bisualmail', function ($app) {
            return new bisualmail;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bisualmail'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/bisualmail.php' => config_path('bisualmail.php'),
        ], 'bisualmail.config');

        $this->publishes([
                __DIR__.'/../public' => public_path('vendor/bisualmail'),
            ], 'public');

        $this->publishes([
                __DIR__.'/../resources/views/templates' => $this->app->resourcePath('views/vendor/bisualmail/templates'),
            ], 'bisualmail.templates');

        $this->publishes([
                __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'bisualmail.migrations');
    }
}
