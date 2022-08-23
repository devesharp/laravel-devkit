<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class HiddenDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric', 'Idade'],
            'active' => ['boolean', 'Ativo' ],
            'send_email' => ['boolean|hidden', 'Ativo' ],
        ];
    }
}
