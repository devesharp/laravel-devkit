<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\TemplateGenerator;

class FactoryGenerator extends TemplateGenerator
{
    public string $resourceType = 'factory';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Factory/factory-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Support\Factory');
        $this->templateData->addImport('{{ $modelNamespace }}\{{ $resourceName }}');
    }

    public function getData(): array
    {
        return [];
    }
}
