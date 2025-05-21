<?php

namespace Tests\Units\Dto\Mocks\Enum;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Illuminate\Validation\Rules\Enum;

enum Type: string
{
    case Rent = 'rent';
    case Sale = 'sale';
    case Exchange = 'exchange';
}