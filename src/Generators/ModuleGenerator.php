<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateData;

class ModuleGenerator
{
    public TemplateData $templateData;

    /**
     * @param TemplateData $templateData
     * @return ModuleGenerator
     */
    public function setTemplateData(TemplateData $templateData): ModuleGenerator
    {
        $this->templateData = $templateData;
        return $this;
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
                if($this->templateData->withController) { $this->generate('controller'); }
                if($this->templateData->withDto) {
                    $this->generate('dto', [ 'template' => 'create' ]);
                    $this->generate('dto', [ 'template' => 'update' ]);
                    $this->generate('dto', [ 'template' => 'search' ]);
                    $this->generate('dto', [ 'template' => 'delete' ]);
                }
                if($this->templateData->withFactory) { $this->generate('factory'); }
                if($this->templateData->withMigration) { $this->generate('migration'); }
                if($this->templateData->withModel) { $this->generate('model'); }
                if($this->templateData->withPolicy) { $this->generate('policy'); }
                if($this->templateData->withPresenter) { $this->generate('presenter'); }
                if($this->templateData->withRepository) { $this->generate('repository'); }
                if($this->templateData->withRouteDocs) { $this->generate('route-docs'); }
                if($this->templateData->withService) { $this->generate('service'); }
                if($this->templateData->withTransformer) { $this->generate('transformer'); }
                if($this->templateData->withTransformerInterface) { $this->generate('interface-transformer'); }
                if($this->templateData->withTestUnit) { $this->generate('test-unit'); }
                if($this->templateData->withTestRoute) { $this->generate('test-route'); }
                return;
            default:
                throw new \Exception('Invalid type');
        }

//        $generator->setCommand($this);
        $generator->setTemplateData(clone $this->templateData);
        $generator->additionalData = $options;
        $generator->handle();
    }
}
