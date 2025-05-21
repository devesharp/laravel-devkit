<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class RemoveRequiredsDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->disableRequiredValues();
        $this->extendRules(CreateDtoStub::class);

        return [
        ];
    }
}
