<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;

class ModelGenerator extends TemplateGenerator
{
    public string $resourceType = 'model';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Model/model-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Support\ModelGetTable');
        $this->templateData->addImport('Illuminate\Database\Eloquent\Model');

        if ($this->templateData->withPresenter) {
            $this->templateData->addImport('{{ $presenterNamespace }}\{{ $resourceName }}Presenter');
            $this->templateData->addImport('Devesharp\Patterns\Presenter\PresentableTrait');
        }

        if ($this->templateData->withFactory) {
            $this->templateData->addImport('{{ $factoryNamespace }}\{{ $resourceName }}Factory');
            $this->templateData->addImport('Illuminate\Database\Eloquent\Factories\HasFactory');
        }
    }

    public function getData(): array
    {
        return [];
    }
}
