<?php

namespace Devesharp\Generators;

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

    protected function generate($type, $data, $options = [])
    {
        switch ($type) {
            case 'formatter':
                return $this->call(MakeFormatter::class, [
                    'name' => $data['module'] . 'Formatter',
                ]);
            case 'controller':
                $generator = app(ControllerGenerator::class);
                break;
            case 'dto':
                $generator = app(DtoGenerator::class);
                break;
            case 'factory':
                $generator = app(FactoryGenerator::class);
                break;
                break;
            case 'migration':
                $generator = app(MigrationGenerator::class);
                break;
            case 'model':
                $generator = app(ModelGenerator::class);
                break;
            case 'policy':
                $generator = app(PolicyGenerator::class);
                break;
            case 'presenter':
                $generator = app(PresenterGenerator::class);
                break;
            case 'repository':
                $generator = app(RepositoryGenerator::class);
                break;
            case 'route-docs':
                $generator = app(RouteDocGenerator::class);
                break;
            case 'service':
                $generator = app(ServiceGenerator::class);
                break;
            case 'test-route':
                $generator = app(TestRouteGenerator::class);
                break;
            case 'test-unit':
                $generator = app(TestUnitGenerator::class);
                break;
            case 'transformer':
                $generator = app(TransformerGenerator::class);
                break;
            case 'interface-transformer':
                $generator = app(TransformerInterfaceGenerator::class);
                break;
            case 'all':
                $this->generate('controller', $data);
                $this->generate('dto', $data, [ 'template' => 'create' ]);
                $this->generate('dto', $data, [ 'template' => 'update' ]);
                $this->generate('dto', $data, [ 'template' => 'search' ]);
                $this->generate('dto', $data, [ 'template' => 'delete' ]);
                $this->generate('factory', $data);
                $this->generate('migration', $data);
                $this->generate('model', $data);
                $this->generate('policy', $data);
                $this->generate('presenter', $data);
                $this->generate('repository', $data);
                $this->generate('route-docs', $data);
                $this->generate('service', $data);
                $this->generate('test-route', $data);
                $this->generate('test-unit', $data);
                $this->generate('transformer', $data);
                $this->generate('interface-transformer', $data);
                return;
            default:
                throw new \Exception('Invalid type');
        }

        $generator->setCommand($this);
        $generator->setData($data, $options);
        $generator->generate();
    }

    public function handle()
    {
        $type = $this->argument('type');

        var_dump($this->argument('module'));

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

        return 0;
    }
}
