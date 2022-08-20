<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeFactoryService extends GeneratorBase
{
    protected $name = 'ds:factory';

    protected $description = 'Create a new factory';

    protected $type = 'Factory';

    protected string $folder = 'Factories';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/factory.stub';
    }
}
