<?php

namespace Devesharp\Patterns\Dto\Templates;

use Devesharp\Patterns\Dto\AbstractDto;

class SearchTemplateDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'strict' => ['boolean', 'No modo strict, os campos não existentes nos filtros lançarão exceção'],
            'query' => 'array',
            'query.limit' => ['numeric', 'Limite de registros'],
            'query.offset' => ['numeric', 'Offset'],
            'query.sort' => ['array', 'Ordenação'],
        ];
    }
}
