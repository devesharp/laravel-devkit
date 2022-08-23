<?php

namespace Devesharp\Patterns\Dto\Templates;

use Devesharp\Patterns\Dto\AbstractDto;

class SearchTemplateDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'query' => 'array',
            'query.limit' => 'numeric',
            'query.offset' => 'numeric',
            'query.sort' => 'array',
        ];
    }
}
