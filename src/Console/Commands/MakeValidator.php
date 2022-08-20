<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeValidator extends GeneratorBase
{
    protected $name = 'ds:validator';

    protected $description = 'Create a new validator';

    protected $type = 'Validator';

    protected string $folder = 'Validators';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/validator.stub';
    }
}