<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;

class TransformerGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'transformer';

    public function getFile(): string
    {
        return 'devesharp-generators::Transformer/transformer-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
