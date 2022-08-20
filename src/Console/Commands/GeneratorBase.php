<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class GeneratorBase extends GeneratorCommand
{
    protected string $folder = '';

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

        $view = str_replace('ServiceName', Str::studly($this->argument('name') ?? $this->argument('module')), $stub);
        $view = str_replace('ModuleName', Str::studly($this->argument('module')), $view);
        $view = str_replace('$namespace', $this->getDefaultNamespace('App'), $view);

        return $view;
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name). $this->type . '.php';
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
        return $rootNamespace . '\Modules\\' . $nameService. '\\' . $this->folder;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of the module'],
            ['name', InputArgument::OPTIONAL, 'The name of the controller'],
        ];
    }

    protected function getStub()
    {
        return '';
    }
}
