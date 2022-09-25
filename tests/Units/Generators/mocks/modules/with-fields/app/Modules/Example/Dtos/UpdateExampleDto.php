<?php

namespace App\Modules\Example\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use App\Modules\Example\Dtos\CreateExampleDto;

class UpdateExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateExampleDto::class);
        $this->disableRequiredValues();

        return [
        ];
    }
}