<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;

class TransformerGenerator extends TemplateGenerator
{

    public string $resourceType = 'transformer';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Transformer/transformer-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Transformer\Transformer');
        $this->templateData->addImport('Devesharp\Support\Formatters\DateTimeISOFormatter');
        $this->templateData->addImport('Devesharp\Support\Formatters\CPFAndCNPJFormatter');
        $this->templateData->addImport('Devesharp\Support\Formatters\CEPFormatter');
        $this->templateData->addImport('Devesharp\Support\Formatters\PhoneFormatter');
        $this->templateData->addImport('Devesharp\Support\Formatters\RGFormatter');
        $this->templateData->addImport('{{ $modelNamespace }}\{{ $resourceName }}');
    }

    public function getData(): array
    {
        return [];
    }
}
