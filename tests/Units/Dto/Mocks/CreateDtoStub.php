<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class CreateDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric|required', 'Idade'],
            'active' => ['boolean', 'Ativo' ],
        ];
    }
}
