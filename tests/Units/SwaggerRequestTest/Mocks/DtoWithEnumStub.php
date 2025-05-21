<?php

namespace Tests\Units\SwaggerRequestTest\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Illuminate\Validation\Rules\Enum;

enum Category: string
{
    case apartment = 'apartment';
    case house = 'house';
    case commercial = 'commercial';
}

enum Type: string
{
    case Rent = 'rent';
    case Sale = 'sale';
    case Exchange = 'exchange';
}

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
