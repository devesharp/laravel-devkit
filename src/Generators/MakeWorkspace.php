<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\FileSystem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MichaelRubel\Formatters\Commands\MakeFormatterCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeWorkspace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:workspace';

    protected $description = 'Command description';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    protected function getOptions()
    {
        return [
            ['template', null, InputOption::VALUE_REQUIRED, 'Yml with workspace config'],
        ];
    }

    public function handle()
    {
        $file = realpath(base_path($this->option('template')));

        app(WorkspaceGenerator::class)
            ->setData([
                'file_template' => $file,
            ])
            ->generate();
        app(FileSystem::class)->render();
    }
}
