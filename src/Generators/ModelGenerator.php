<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class ModelGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'model';

    public function getFile(): string
    {
        return 'devesharp-generators::Model/model-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
