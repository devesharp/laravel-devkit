<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class AcceptAdditionalValuesDtoStub extends AbstractDto
{
    protected bool $additionalProperties = true;

    protected function configureValidatorRules(): array
    {
        return [];
    }
}
