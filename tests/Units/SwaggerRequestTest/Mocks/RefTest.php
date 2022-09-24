<?php

namespace Tests\Units\SwaggerRequestTest\Mocks;

use Devesharp\SwaggerGenerator\Utils\Ref;

class RefTest extends Ref
{
    public $name = 'PropertyType';

    protected function configure(): void {
        $this->setEnum('type', ['rent', 'sale'], 'Tipo de Imóvel');
    }
}
