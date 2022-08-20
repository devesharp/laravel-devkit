<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakePolicy extends GeneratorBase
{
    protected $name = 'ds:policy';

    protected $description = 'Create a new policy';

    protected $type = 'Policy';

    protected string $folder = 'Policies';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/policy.stub';
    }
}
