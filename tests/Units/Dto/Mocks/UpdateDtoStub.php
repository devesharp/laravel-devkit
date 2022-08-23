<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class UpdateDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateDtoStub::class);

        return [
            'active' => null, // remove active in update
        ];
    }
}
