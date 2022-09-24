<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Devesharp\Patterns\Dto\Templates\SearchTemplateDto;

class SearchDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(SearchTemplateDto::class);

        return [
            'filters.name' => new Rule('string'),
            'filters.full_name' => new Rule('string'),
        ];
    }
}
