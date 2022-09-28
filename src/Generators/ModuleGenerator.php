<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;

class ModuleGenerator
{
    public array $data = [];

    function setData($data)
    {
        $this->data = $data;
    }

    function generate($type, $options = [])
    {
        switch ($type) {
//            case 'formatter':
//                return $this->call(MakeFormatter::class, [
//                    'name' => $data['module'] . 'Formatter',
//                ]);
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
                if($this->data['withController']) { $this->generate('controller'); }
                if($this->data['withDto']) {
                    $this->generate('dto', [ 'template' => 'create' ]);
                    $this->generate('dto', [ 'template' => 'update' ]);
                    $this->generate('dto', [ 'template' => 'search' ]);
                    $this->generate('dto', [ 'template' => 'delete' ]);
                }
                if($this->data['withFactory']) { $this->generate('factory'); }
                if($this->data['withMigration']) { $this->generate('migration'); }
                if($this->data['withModel']) { $this->generate('model'); }
                if($this->data['withPolicy']) { $this->generate('policy'); }
                if($this->data['withPresenter']) { $this->generate('presenter'); }
                if($this->data['withRepository']) { $this->generate('repository'); }
                if($this->data['withRouteDocs']) { $this->generate('route-docs'); }
                if($this->data['withService']) { $this->generate('service'); }
                if($this->data['withTransformer']) { $this->generate('transformer'); }
                if($this->data['withTransformerInterface']) { $this->generate('interface-transformer'); }
                if($this->data['withTestUnit']) { $this->generate('test-unit'); }
                if($this->data['withTestRoute']) { $this->generate('test-route'); }
                return;
            default:
                throw new \Exception('Invalid type');
        }

        $generator->setCommand($this);
        $generator->setData($this->data, $options);
        $generator->generate();
    }
}
