<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;

class MigrationGenerator extends TemplateGenerator
{
    public string $resourceType = 'migration';

    public function getFileName(): string
    {
        return $this->templateData->now . '_create_' . $this->templateData->tableName . '_table.php';
    }

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Migration/migration-table';
    }

    public function getData(): array
    {
        return [];
    }
}
