<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;

class TransformerInterfaceGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'transformer-interface';

    public function getFile(): string
    {
        return 'devesharp-generators::Transformer/interface-transformer-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
