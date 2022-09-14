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

    public function getFileName()
    {
        return date('Y_m_d_His') . '_create_' . $this->getRootData()['tableName'] . '_table.php';
    }

    public function getData()
    {
        return [
        ];
    }
}
