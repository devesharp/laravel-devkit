<?php

namespace Tests\Units\SwaggerRequestTest\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class ComplexDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'pets.*' => new Rule('array|required', 'Animais do usuário'),
            'pets.*.id' => new Rule('numeric|required', 'ID'),
            'pets.*.name' => new Rule('string|required', 'Nome do Pet'),
            'owner' => new Rule('array|required', 'Dados do Dono'),
            'owner.id' => new Rule('numeric', 'ID do Dono'),
            'owner.name' => new Rule('string', 'Nome do Dono'),
            'owner.age' => new Rule('string', 'Idade do Dono'),
            'item_array_deep' => new Rule('array|required'),
            'item_array_deep.*.id' => new Rule('numeric'),
            'item_array_deep.*.name' => new Rule('string'),
            'item_array_deep.*.items' => new Rule('array|required'),
            'item_array_deep.*.items.*.id' => new Rule('numeric'),
            'item_array_deep.*.items.*.name' => new Rule('string'),
        ];
    }
}
