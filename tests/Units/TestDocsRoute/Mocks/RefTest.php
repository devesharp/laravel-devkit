<?php

namespace Tests\Units\TestDocsRoute\Mocks;

use Devesharp\APIDocs\Ref;

class RefTest extends Ref
{
    public $name = 'PropertyType';

    public function __construct()
    {
        $this->setEnum('type', ['rent', 'sale'], 'Tipo de Imóvel');
    }
}
