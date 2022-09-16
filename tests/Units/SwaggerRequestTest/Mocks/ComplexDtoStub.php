<?php

namespace Tests\Units\SwaggerRequestTest\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class ComplexDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'pets.*' => ['array|required', 'Animais do usuário'],
            'pets.*.id' => ['numeric|required', 'ID'],
            'pets.*.name' => ['string|required', 'Nome do Pet'],
            'owner' => ['array|required', 'Dados do Dono'],
            'owner.id' => ['numeric', 'ID do Dono'],
            'owner.name' => ['string', 'Nome do Dono'],
            'owner.age' => ['string', 'Idade do Dono'],
            'item_array_deep' => 'array|required',
            'item_array_deep.*.id' => 'numeric',
            'item_array_deep.*.name' => 'string',
            'item_array_deep.*.items' => 'array|required',
            'item_array_deep.*.items.*.id' => 'numeric',
            'item_array_deep.*.items.*.name' => 'string',
        ];
    }
}
