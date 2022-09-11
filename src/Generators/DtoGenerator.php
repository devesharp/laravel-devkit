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

    public function getData()
    {
        return [
            'fields' => [
                [
                    'name' => 'id',
                    'rules' => 'required',
                    'description' => 'required',
                ]
            ],
        ];
    }
}
