<?php

namespace App\Modules\ModuleExample\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto;

class DeleteResourceExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(ActionManyTemplateDto::class);

        return [
        ];
    }
}