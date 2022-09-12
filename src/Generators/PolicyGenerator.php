<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class PolicyGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'policy';

    public function getFile(): string
    {
        return 'devesharp-generators::Policy/policy-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
