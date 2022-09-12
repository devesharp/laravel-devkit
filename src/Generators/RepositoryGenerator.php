<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class RepositoryGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'repository';

    public function getFile(): string
    {
        return 'devesharp-generators::Repository/repository-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
