<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\TemplateGenerator;

class RepositoryGenerator extends TemplateGenerator
{
    public string $resourceType = 'repository';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Repository/repository-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Repository\RepositoryMysql');
    }

    public function getData(): array
    {
        return [
            'disableEnabledColumn' => !($this->templateData->fieldsRaw['enabled'] ?? false),
        ];
    }
}
