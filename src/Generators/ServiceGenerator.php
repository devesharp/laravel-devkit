<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\FileTemplateManager;
use Devesharp\Generators\Common\TemplateGenerator;

class ServiceGenerator extends TemplateGenerator
{
    public string $resourceType = 'service';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Service/service-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Service\Service');
        $this->templateData->addImport('Devesharp\Patterns\Service\ServiceFilterEnum');
        $this->templateData->addImport('Devesharp\Patterns\Transformer\Transformer');
        $this->templateData->addImport('{{ $dtoNamespace }}\Create{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $dtoNamespace }}\Update{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $dtoNamespace }}\Search{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $dtoNamespace }}\Delete{{ $resourceName }}Dto');
        $this->templateData->addImport('Devesharp\Support\Collection');
        $this->templateData->addImport('Devesharp\Support\Formatters\OnlyLettersNumbersFormatter');
        $this->templateData->addImport('Illuminate\Support\Facades\DB');
    }

    public function getData(): array
    {
        return [
            'disableEnabledColumn' => !($this->templateData->fieldsRaw['enabled'] ?? false),
        ];
    }
}
