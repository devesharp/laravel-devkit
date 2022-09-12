<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;

class RouteDocGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'route-docs';

    public function getFile(): string
    {
        return 'devesharp-generators::Docs/route-docs-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
