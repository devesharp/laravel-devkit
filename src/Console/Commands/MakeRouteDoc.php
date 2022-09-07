<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeRouteDoc extends GeneratorBase
{
    protected $name = 'ds:route-docs';

    protected $description = 'Create a new route doc';

    protected $type = 'RouteDoc';

    protected string $folder = 'Docs';

    protected function replaceClass($stub, $name)
    {
        $view = parent::replaceClass($stub, $name);
        $view = str_replace('NamePtBr', @$this->option('namePtBr') ?? Str::studly($this->argument('module')), $view);

        return $view;
    }

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/route-docs.stub';
    }
}