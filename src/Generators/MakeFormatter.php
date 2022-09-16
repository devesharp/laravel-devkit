<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\GeneratorConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MichaelRubel\Formatters\Commands\MakeFormatterCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeFormatter extends MakeFormatterCommand
{

    protected function resolveStubPath(string $stub): string
    {
        return __DIR__ . '/Stubs/Formatters/formatter.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $config = app(GeneratorConfig::class);
        $config->init();
        return $config->getNamespace('formatter');
    }
}
