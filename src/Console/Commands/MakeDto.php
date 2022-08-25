<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeDto extends GeneratorBase
{
    protected $name = 'ds:dto';

    protected $description = 'Create a new Dto';

    protected $type = 'Dto';

    protected string $folder = 'Dto';

    protected function getStub()
    {
        if ($this->option('type') == 'search') {
            return  __DIR__ . '/Stubs/dto-search.stub';
        } else if ($this->option('type') == 'update') {
            return  __DIR__ . '/Stubs/dto-update.stub';
        } if ($this->option('type') == 'delete') {
            return  __DIR__ . '/Stubs/dto-delete.stub';
        } else {
            return  __DIR__ . '/Stubs/dto.stub';
        }
    }

    protected function replaceClass($stub, $name)
    {
        $view = str_replace('CreateServiceName', 'Create' . str_replace('Update', '', Str::studly($this->argument('name') ?? $this->argument('module'))), $stub);
        $view = str_replace('ServiceName', Str::studly($this->argument('name') ?? $this->argument('module')), $view);
        $view = str_replace('ModuleName', Str::studly($this->argument('module')), $view);
//        $view = str_replace('NamePtBr', @$this->option('namePtBr') ?? Str::studly($this->argument('module')), $view);
        $view = str_replace('$namespace', $this->getDefaultNamespace('App'), $view);

        return $view;
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
            ['name', InputArgument::REQUIRED, 'The name of the controller'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['type', 't', InputOption::VALUE_NONE, 'Template dto'],
        ];
    }
}