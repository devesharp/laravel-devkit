<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeUnitTestService extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:unit-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test for service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Unit Test';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($view, $name)
    {
        $view = parent::replaceClass($view, $name);

        $view = str_replace('{{header_tests}}', config('devesharp.commands.snippets.unit-tests.header-test', 'API Docs'), $view);
        $view = str_replace('{{use_namespace}}', config('devesharp.commands.snippets.unit-tests.header-namespaces', 'API Docs'), $view);
        $view = str_replace('ServiceName', Str::studly($this->argument('name')), $view);
        $view = str_replace('ModuleName', Str::studly($this->argument('module') ?? $this->argument('name')), $view);

        return $view;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/Stubs/unit-tests-service.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->basePath().'/tests/'.str_replace('\\', '/', $name).'Test.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Unit\\' . Str::studly($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module of the service'],
            ['name', InputArgument::OPTIONAL, 'The name of the service'],
        ];
    }
}
