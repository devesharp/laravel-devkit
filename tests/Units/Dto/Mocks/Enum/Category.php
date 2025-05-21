<?php

namespace Tests\Units\Dto\Mocks\Enum;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Illuminate\Validation\Rules\Enum;

enum Category: string
{
    case apartment = 'apartment';
    case house = 'house';
    case commercial = 'commercial';
}