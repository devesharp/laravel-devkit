<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class FactoryGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'factory';

    public function getFile(): string
    {
        return 'devesharp-generators::Factory/factory-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
