<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeTransformer extends GeneratorBase
{
    protected $name = 'ds:transformer';

    protected $description = 'Create a new transformer';

    protected $type = 'Transformer';

    protected string $folder = 'Transformers';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/transformer.stub';
    }
}