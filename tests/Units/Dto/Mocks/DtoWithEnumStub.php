<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Illuminate\Validation\Rules\Enum;
use Tests\Units\Dto\Mocks\Enum\Category;
use Tests\Units\Dto\Mocks\Enum\Type;

class DtoWithEnumStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'category' => new Rule([new Enum(Category::class), 'required'], 'Categoria do produto'),
            'type' => new Rule([new Enum(Type::class), 'required']),
        ];
    }
}
