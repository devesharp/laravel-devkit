<?php

namespace Devesharp\Console;

use Devesharp\Console\Commands\MakeController;
use Devesharp\Console\Commands\MakeFactoryService;
use Devesharp\Console\Commands\MakeMigration;
use Devesharp\Console\Commands\MakeModel;
use Devesharp\Console\Commands\MakePolicy;
use Devesharp\Console\Commands\MakePresenter;
use Devesharp\Console\Commands\MakeRepository;
use Devesharp\Console\Commands\MakeAll;
use Devesharp\Console\Commands\MakeRoute;
use Devesharp\Console\Commands\MakeRouteDoc;
use Devesharp\Console\Commands\MakeRouteTestService;
use Devesharp\Console\Commands\MakeService;
use Devesharp\Console\Commands\MakeTransformer;
use Devesharp\Console\Commands\MakeUnitTestService;
use Devesharp\Console\Commands\MakeDto;
use Devesharp\Console\Commands\MakeDictionary;
use UpInside\LaravelMakeTrait\Commands\TraitMakeCommand;
use Illuminate\Support\ServiceProvider;

class MakeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-generator');

        /**
         * Register makes
         */
        if ($this->app->runningInConsole()) {
            $path = realpath(__DIR__ . '/../../config/config.php');
            $this->publishes([$path => config_path('devesharp.php')], 'config');
            $this->mergeConfigFrom($path, 'devesharp');

            $this->commands([
                MakeAll::class,
                MakeModel::class,
                MakeRouteDoc::class,
                MakeRoute::class,
                MakeMigration::class,
                MakePresenter::class,
                MakeService::class,
                MakeRepository::class,
                MakeUnitTestService::class,
                MakeRouteTestService::class,
                MakeTransformer::class,
                MakeDto::class,
                MakeFactoryService::class,
                MakeController::class,
                MakePolicy::class,
            ]);
        }
    }
}
