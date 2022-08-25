<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeModel extends GeneratorBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__ . '/Stubs/model.stub';
    }

    protected function replaceClass($stub, $name)
    {
        $view = parent::replaceClass($stub, $name);
        $name = Str::snake(trim($this->input->getArgument('name')));
        $view = str_replace('{{table}}', $name, $view);

        return $view;
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $nameService = Str::studly($this->argument('module'));
        return $rootNamespace . '\Modules\\' . $nameService. '\Models';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
//        CreateFlightsTable
        return [
            ['module', InputArgument::REQUIRED, 'The name of the module'],
            ['name', InputArgument::OPTIONAL, 'The name of the model'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('migration')) {
            $this->createMigration();
        }
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $this->call('ds:migration', [ 'name' => $this->argument('name') ?? $this->argument('module') ]);
    }
}
