<?php

namespace Devesharp\Patterns\Dto\Templates;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

/**
 * Dto para ações em massa usado em Service::makeSelectActions()
 */
class ActionManyTemplateDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'id' => new Rule('numeric'),
            'ids' => new Rule('array'),
            'filters' => new Rule('array'),
            'enable_select_all' => new Rule('bool', 'Para deletar todos os itens, deve ser enviado filters = [] e enable_select_all = true. Essa variável serve como proteção para não deletar todos os itens sem querer.'),
        ];
    }
}
