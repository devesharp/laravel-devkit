<?php

namespace Devesharp\Generators\Provider;

use Devesharp\Generators\MakeAll;
use Illuminate\Support\ServiceProvider;

class GeneratorsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Stubs', 'devesharp-generators');

        $this->app->singleton(\Devesharp\Generators\Common\FileSystem::class, function ($app) {
            return new \Devesharp\Generators\Common\FileSystem();
        });

        $path = realpath(__DIR__ . '/../../../config/devesharp_generator.php');
        $this->publishes([$path => config_path('devesharp_generator.php')], 'devesharp-laravel-devit-config');
        $this->mergeConfigFrom($path, 'devesharp_generator');

        /**
         * Register makes
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAll::class
            ]);
        }
    }
}
