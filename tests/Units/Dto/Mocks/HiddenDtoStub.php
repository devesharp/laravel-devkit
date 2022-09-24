<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class HiddenDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => new Rule('string|max:100|required', 'Nome'),
            'age' => new Rule('numeric', 'Idade'),
            'active' => new Rule('boolean', 'Ativo' ),
            'send_email' => new Rule('boolean|hidden', 'Ativo' ),
        ];
    }
}
