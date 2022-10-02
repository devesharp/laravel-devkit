<?php

namespace Devesharp\Generators\Provider;

use Devesharp\Generators\MakeAll;
use Devesharp\Generators\MakeWorkspace;
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

        $path = realpath(__DIR__ . '/../../../config/devesharp_dev_kit.php');
        $this->publishes([$path => config_path('devesharp_dev_kit.php')], 'devesharp-laravel-devit-config');
        $this->mergeConfigFrom($path, 'devesharp_dev_kit');

        /**
         * Register makes
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAll::class,
                MakeWorkspace::class,
            ]);
        }
    }
}
