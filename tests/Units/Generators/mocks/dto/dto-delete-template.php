<?php

namespace App\Modules\Posts\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto;

class DeletePostsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(ActionManyTemplateDto::class);

        return [
        ];
    }
}