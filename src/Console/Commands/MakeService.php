<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeService extends GeneratorBase
{
    protected $name = 'ds:service';

    protected $description = 'Create a new service';

    protected $type = 'Service';

    protected string $folder = 'Services';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/service.stub';
    }
}
