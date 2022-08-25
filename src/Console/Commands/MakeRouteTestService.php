<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeRouteTestService extends GeneratorBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:route-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tests for routes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'TestRoute';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $view = str_replace('ServiceName', Str::studly($this->argument('name')), $stub);
        $view = str_replace('{{route}}', Str::kebab($this->argument('name')), $view);
        $view = str_replace('{{header_tests}}', config('devesharp.commands.snippets.unit-tests.header-test', 'API Docs'), $view);
        $view = str_replace('{{use_namespace}}', config('devesharp.commands.snippets.unit-tests.header-namespaces', 'API Docs'), $view);
        $view = str_replace('ServiceName', Str::studly($this->argument('name')), $view);
        $view = str_replace('ModuleName', Str::studly($this->argument('module') ?? $this->argument('name')), $view);

        return $view;
    }


    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->basePath().'/tests/'.str_replace('\\', '/', $name).'RouteTest.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/Stubs/routes-tests-service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $nameService = Str::studly($this->argument('module'));
        return $rootNamespace.'\Routes\\' . $nameService;
    }
}
