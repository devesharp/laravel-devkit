<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\TemplateGenerator;

class PolicyGenerator extends TemplateGenerator
{
    public string $resourceType = 'policy';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Policy/policy-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('App\Modules\Users\Interfaces\UsersPermissions');
    }

    public function getData(): array
    {
        return [];
    }
}
