<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\TemplateGenerator;

class RouteDocGenerator extends TemplateGenerator
{
    public string $resourceType = 'route-docs';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Docs/route-docs-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\APIDocs\RoutesDocAbstract');
        $this->templateData->addImport('Devesharp\APIDocs\RoutesDocInfo');
    }

    public function getData(): array
    {
        return [];
    }
}
