<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;

class PresenterGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'presenter';

    public function getFile(): string
    {
        return 'devesharp-generators::Presenter/presenter-default';
    }

    public function getData()
    {
        return [
        ];
    }
}
