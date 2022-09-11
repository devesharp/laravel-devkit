<?php

namespace App\Modules\ModuleExample\Dto;

use Devesharp\Patterns\Dto\AbstractDto;

class ResourceExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
        ];
    }
}