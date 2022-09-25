<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\FileSystem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MichaelRubel\Formatters\Commands\MakeFormatterCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:make';

    protected $description = 'Command description';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['type', InputArgument::REQUIRED, 'The type of the service.'],
            ['module', InputArgument::REQUIRED, 'The module of the service.'],
            ['name', InputArgument::OPTIONAL, 'The name of the service.']
        ];
    }

    protected function getOptions()
    {
        return [
            ['fieldsFile', null, InputOption::VALUE_OPTIONAL, 'Fields input as json file'],
//            ['skip', null, InputOption::VALUE_OPTIONAL, 'Skip Specific Items to Generate ()'],
        ];
    }

    public function handle()
    {
        $type = $this->argument('type');

        $data = [
            'module' => $this->argument('module'),
            'name' => $this->argument('name') ?? $this->argument('module'),
            'file_template' => $this->option('fieldsFile') ? base_path($this->option('fieldsFile')) :  '',
            'withController' => $type == 'all' || $type == 'controller',
            'withDto' => $type == 'all' || $type == 'dto',
            'withService' => $type == 'all' || $type == 'service',
            'withFactory' => $type == 'all' || $type == 'factory',
            'withModel' => $type == 'all' || $type == 'model',
            'withPolicy' => $type == 'all' || $type == 'policy',
            'withPresenter' => $type == 'all' || $type == 'presenter',
            'withRepository' => $type == 'all' || $type == 'repository',
            'withRouteDocs' => $type == 'all' || $type == 'route-docs',
            'withTransformerInterface' => $type == 'all' || $type == 'transformer-interface',
            'withTransformer' => $type == 'all' || $type == 'transformer',
            'withTestRoute' => $type == 'all' || $type == 'test-route',
            'withTestUnit' => $type == 'all' || $type == 'test-unit',
        ];

        $this->generate($type, $data);

        app(FileSystem::class)->render();

        return 0;
    }
}
