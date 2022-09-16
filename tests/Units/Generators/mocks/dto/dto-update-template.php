<?php

namespace App\Modules\ModuleExample\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use App\Modules\ModuleExample\Dtos\CreateResourceExampleDto;

class UpdateResourceExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateResourceExampleDto::class);
        $this->disableRequiredValues();

        return [
        ];
    }
}