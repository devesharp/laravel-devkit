<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class DeepDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'deep' => new Rule([CreateDtoStub::class, 'required'] , 'Nome'), // remove active in update
        ];
    }
}
