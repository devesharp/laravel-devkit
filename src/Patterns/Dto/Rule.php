<?php

namespace Devesharp\Patterns\Dto;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;
use Illuminate\Support\Str;

class Rule
{
    protected $dto;

    public function __construct(public array|string|AbstractDto $rules, public string $description = '')
    {
    }
}