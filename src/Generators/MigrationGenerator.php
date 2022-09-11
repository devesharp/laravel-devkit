<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class MigrationGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'migration';

    public function getFile(): string
    {
        return 'devesharp-generators::Migration/migration-table';
    }

    public function getData()
    {
        return [
        ];
    }
}
