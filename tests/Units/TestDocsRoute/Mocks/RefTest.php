<?php

namespace Tests\Units\TestDocsRoute\Mocks;

use Devesharp\SwaggerGenerator\Ref;

class RefTest extends Ref
{
    public $name = 'PropertyType';

    protected function configure(): void {
        $this->setEnum('type', ['rent', 'sale'], 'Tipo de Imóvel');
    }
}
