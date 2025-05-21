<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class CreateDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => new Rule('string|required|max:100|required', 'Nome'),
            'age' => new Rule('numeric|required', 'Idade'),
            'active' => new Rule('boolean|required_if:name,==,1', 'Ativo' ),
        ];
    }
}
