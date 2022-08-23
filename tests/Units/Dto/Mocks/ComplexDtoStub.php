<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class ComplexDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric|required', 'Idade'],
            'active' => ['boolean', 'Ativo'],
            'pets.*' => ['array|required', 'ID'],
            'pets.*.id' => ['numeric|required', 'ID'],
            'pets.*.name' => ['string|required', 'Nome do Pet'],
            'owner' => ['array|required', 'ID do Dono'],
            'owner.id' => ['numeric|array', 'ID do Dono'],
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
