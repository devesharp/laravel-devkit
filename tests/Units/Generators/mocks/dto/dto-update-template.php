<?php

namespace App\Modules\Posts\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use App\Modules\Posts\Dtos\CreatePostsDto;

class UpdatePostsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreatePostsDto::class);
        $this->disableRequiredValues();

        return [
        ];
    }
}