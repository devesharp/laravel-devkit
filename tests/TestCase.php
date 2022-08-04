<?php

namespace Tests;

//use PHPUnit\Framework\TestCase as BaseTestCase;
use Devesharp\Console\MakeProvider;
use Illuminate\Database\Schema\Blueprint;
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

        Schema::create('model_repository_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->default(true);
            $table->string('name');
            $table->integer('age');
            $table->timestampsTz();
        });
    }

    protected function getPackageProviders($app)
    {
        return [MakeProvider::class];
    }
}
