<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class RemoveRequiredsDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->disableRequiredValues();
        $this->extendRules(CreateDtoStub::class);

        return [];
    }
}
