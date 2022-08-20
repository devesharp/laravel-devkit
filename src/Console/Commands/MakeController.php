<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeController extends GeneratorBase
{
    protected $name = 'ds:controller';

    protected $description = 'Create a new controller';

    protected $type = 'Controller';

    protected string $folder = 'Controllers';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/controller.stub';
    }
}
