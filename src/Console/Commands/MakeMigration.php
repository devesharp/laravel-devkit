<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeMigration extends GeneratorBase
{
    protected $name = 'ds:migration';

    protected $description = 'Create a new Migration';

    protected $type = 'Migration';

    protected string $folder = 'Migration';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/migration.stub';
    }

    protected function replaceClass($view, $name)
    {
        $name = Str::snake(trim($this->input->getArgument('name')));

        $view = str_replace('{{ table }}', $name, $view);

        return $view;
    }

    protected function getPath($name)
    {
        $name = Str::snake(trim($this->input->getArgument('name') ?? $this->input->getArgument('module')));
        return 'database/migrations/'. date('Y_m_d_His') . '_create_' . $name . '_table.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller'],
        ];
    }

    protected function getOptions()
    {
        return [
        ];
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Database\Migrations';
    }
}