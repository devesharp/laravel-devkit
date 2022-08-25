<?php

namespace Devesharp\Patterns\Dto\Templates;

use Devesharp\Patterns\Dto\AbstractDto;

/**
 * Dto para ações em massa usado em Service::makeSelectActions()
 */
class ActionManyTemplateDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'id' => 'numeric',
            'ids' => 'array',
            'filters' => 'array',
            'enable_select_all' => ['bool', 'Para deletar todos os itens, deve ser enviado filters = [] e enable_select_all = true. Essa variável serve como proteção para não deletar todos os itens sem querer.'],
        ];
    }
}
