<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;

class PresenterGenerator extends TemplateGenerator
{
    public string $resourceType = 'presenter';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Presenter/presenter-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Presenter\Presenter');
    }

    public function getData(): array
    {
        return [];
    }
}