<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class ControllerGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'controller';

    public function getFile(): string
    {
        return 'devesharp-generators::Controller/controller';
    }

    public function getData()
    {
        return [];
    }
}
