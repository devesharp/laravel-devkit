<?php

namespace Tests\Units\Validators\Mocks;

use Devesharp\Validator\Validator;
use Devesharp\Validator\ValidatorAPIGenerator;

class ValidatorStubWithGenerator extends Validator
{
    use ValidatorAPIGenerator;

    protected array $rules = [
        'create' => [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => 'numeric|required',
            'active' => 'boolean',
        ],
        'update' => [
            '_extends' => 'create',
            'id' => 'numeric',
        ],
        'complex' => [
            '_extends' => 'create',
            'item_array.*.id' => 'numeric',
            'item_array.*.name' => 'string',
            'item_object.id' => 'numeric',
            'item_object.name' => 'string',
            'item_array_deep.*.id' => 'numeric',
            'item_array_deep.*.name' => 'string',
            'item_array_deep.*.items' => 'string',
            'item_array_deep.*.items.*.id' => 'numeric',
            'item_array_deep.*.items.*.name' => 'string',
        ],
        // Busca
        'search' => [
            'filters.name' => 'string',
            'filters.full_name' => 'string',
        ],
    ];


    public function create(array $data, $requester = null)
    {
        $context = 'create';

        return $this->validate($data, $this->getValidate($context));
    }

    public function update(array $data, $requester = null)
    {
        $context = 'update';

        return $this->validate($data, $this->getValidateWithoutRequired($context));
    }

    public function search(array $data, $requester = null)
    {
        return $this->validate($data, $this->getValidateWithSearch('search'));
    }

    public function complex(array $data, $requester = null)
    {
        return $this->validate($data, $this->getValidateWithSearch('complex'));
    }
}
