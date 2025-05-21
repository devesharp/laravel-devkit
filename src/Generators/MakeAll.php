<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\FileSystem;
use Devesharp\Generators\Common\TemplateData;
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
            ['all', 'all', InputOption::VALUE_OPTIONAL, 'All layers'],
            ['fieldsFile', null, InputOption::VALUE_OPTIONAL, 'Fields input as json file'],
//            ['skip', null, InputOption::VALUE_OPTIONAL, 'Skip Specific Items to Generate ()'],
        ];
    }

    public function handle()
    {
        $type = $this->argument('type');

        $data = null;
        if (!empty($this->option('fieldsFile'))) {
            $templateData = TemplateData::makeByFile($this->option('fieldsFile'));
            if (empty($templateData->moduleName)) {
                $templateData->moduleName = $this->argument('module');
                $templateData->resourceName = $this->argument('name') ?? $this->argument('module');
            }
        } else {
            $templateData = new TemplateData(
                moduleName: $this->argument('module'),
                resourceName: $this->argument('name') ?? $this->argument('module'),
                withController: $this->option('all') || $this->confirm('Create Controller?'),
                withDto: $this->option('all') || $this->confirm('Create Dto?'),
                withFactory: $this->option('all') || $this->confirm('Create Factory?'),
                withMigration: $this->option('all') || $this->confirm('Create Migration?'),
                withModel: $this->option('all') || $this->confirm('Create Model?'),
                withPolicy: $this->option('all') || $this->confirm('Create Policy?'),
                withPresenter: $this->option('all') || $this->confirm('Create Presenter?'),
                withRepository: $this->option('all') || $this->confirm('Create Repository?'),
                withRouteDocs: $this->option('all') || $this->confirm('Create Route Docs?'),
                withService: $this->option('all') || $this->confirm('Create Service?'),
                withTransformer: $this->option('all') || $this->confirm('Create Transformer?'),
                withTransformerInterface: $this->option('all') || $this->confirm('Create Transformer Interface?'),
                withTestUnit: $this->option('all') || $this->confirm('Create Test Unit?'),
                withTestRoute: $this->option('all') || $this->confirm('Create Test Route?'),
            );
        }

        $moduleGenerator = app(ModuleGenerator::class);
        $moduleGenerator->setTemplateData($templateData);
        $moduleGenerator->generate($type);

        app(FileSystem::class)->render();

        return 0;
    }
}
