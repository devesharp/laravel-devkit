<?php

namespace Devesharp\Patterns\Dto\Templates;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class SearchTemplateDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'strict_filters' => new Rule('boolean', 'No modo strict, os campos não existentes nos filtros lançarão exceção. Default: true'),
            'query' => new Rule('array'),
            'query.limit' => new Rule('numeric', 'Limite de registros'),
            'query.offset' => new Rule('numeric', 'Offset'),
            'query.sort' => new Rule('string', 'Ordenação'),
        ];
    }
}
