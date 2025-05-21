<?php

namespace App\Modules\Example\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto;

class DeleteExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(ActionManyTemplateDto::class);

        return [
        ];
    }
}