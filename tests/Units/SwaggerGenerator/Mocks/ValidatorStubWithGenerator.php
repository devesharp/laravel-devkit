<?php

namespace Tests\Units\SwaggerGenerator\Mocks;

use Devesharp\Validator\Validator;
use Devesharp\Validator\ValidatorAPIGenerator;

class ValidatorStubWithGenerator extends \Devesharp\Patterns\Validator\Validator
{
    use \Devesharp\Patterns\Validator\ValidatorAPIGenerator;

    protected array $rules = [
        'create' => [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric|required', 'Idade'],
            'active' => ['boolean', 'Ativo' ],
        ],
        'complex' => [
            '_extends' => 'create',
            'pets.*' => ['array|required', 'ID'],
            'pets.*.id' => ['numeric|required', 'ID'],
            'pets.*.name' => ['string|required', 'Nome do Pet'],
            'owner.id' => ['numeric', 'ID do Dono'],
            'owner.name' => ['string', 'Nome do Dono'],
            'owner.age' => ['string', 'Idade do Dono'],
            'item_array_deep.*.id' => 'numeric',
            'item_array_deep.*.name' => 'string',
            'item_array_deep.*.items' => 'array|required',
            'item_array_deep.*.items.*.id' => 'numeric',
            'item_array_deep.*.items.*.name' => 'string',
        ],
    ];

}
