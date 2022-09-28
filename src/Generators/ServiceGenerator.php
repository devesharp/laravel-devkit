<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\FileTemplateManager;

class ServiceGenerator extends BaseGeneratorAbstract
{
    public string $resourceType = 'service';

    public function getFile(): string
    {
        return 'devesharp-generators::Service/service-default';
    }

    public function getData()
    {
        return [
            'filtersSearchable' => !empty($this->fileTemplate) ? (new FileTemplateManager($this->fileTemplate))->getFiltersSearchable() : [],
            'filtersSort' => !empty($this->fileTemplate) ? (new FileTemplateManager($this->fileTemplate))->getFiltersSort() : [],
            'valueOnCreate' => !empty($this->fileTemplate) ? (new FileTemplateManager($this->fileTemplate))->getUsersServiceRelation() : [],
        ];
    }
}
