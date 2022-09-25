<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class DtoGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'dto';

    public function getFile(): string
    {
        return 'devesharp-generators::Dto/dto-default';
    }

    public function getFileName()
    {
        if (!empty($this->options['template'])) {
            if ($this->options['template'] == 'create') {
                return 'Create' . parent::getFileName();
            }else if ($this->options['template'] == 'update') {
                return 'Update' . parent::getFileName();
            }else if ($this->options['template'] == 'delete') {
                return 'Delete' . parent::getFileName();
            }else if ($this->options['template'] == 'search') {
                return 'Search' . parent::getFileName();
            }
        }

        return parent::getFileName();
    }

    public function getData()
    {
        return [
        ];
    }
}
