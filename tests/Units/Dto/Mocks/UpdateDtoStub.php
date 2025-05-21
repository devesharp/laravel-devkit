<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class UpdateDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateDtoStub::class);
        $this->disableRequiredValues();

        return [
            'active' => null, // remove active in update
        ];
    }
}
