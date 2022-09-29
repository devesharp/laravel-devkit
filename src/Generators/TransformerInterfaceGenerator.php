<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;

class TransformerInterfaceGenerator extends TemplateGenerator
{

    public string $resourceType = 'transformer-interface';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Transformer/interface-transformer-default';
    }

    function loadImports(): void {
    }

    public function getData(): array
    {
        return [];
    }
}
