<?php

namespace Tests;

//use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        Schema::create('model_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('age');
            $table->integer('user_create')->default(0);
            $table->timestampsTz();
        });

        Schema::create('model_stubs2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('age');
            $table->integer('user_create')->default(0);
            $table->integer('user_edit')->default(0);
            $table->timestampsTz();
        });

        Schema::create('model_repository_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->default(true);
            $table->string('name');
            $table->integer('age');
            $table->timestampsTz();
        });

        Config::set('devesharp_generator.namespace', [
            'controller' => 'App\Modules\{{ModuleName}}\Resources\Controllers',
            'dto' => 'App\Modules\{{ModuleName}}\Dtos',
            'service' => 'App\Modules\{{ModuleName}}\Services',
            'factory' => 'App\Modules\{{ModuleName}}\Resources\Factories',
            'model' => 'App\Modules\{{ModuleName}}\Resources\Models',
            'policy' => 'App\Modules\{{ModuleName}}\Policies',
            'presenter' => 'App\Modules\{{ModuleName}}\Resources\Presenters',
            'repository' => 'App\Modules\{{ModuleName}}\Resources\Repositories',
            'routeDocs' => 'App\Modules\{{ModuleName}}\Supports\Docs',
            'transformerInterface' => 'App\Modules\{{ModuleName}}\Interfaces',
            'transformer' => 'App\Modules\{{ModuleName}}\Transformers',
            'migration' => 'database/migrations',
            'testRoute' => 'Tests\Routes\{{ModuleName}}',
            'testUnit' => 'Tests\Units\{{ModuleName}}',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [\Devesharp\Generators\Provider\GeneratorsProvider::class];
    }
}
