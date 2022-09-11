<?php

namespace App\Modules\ModuleExample\Dto;

use Devesharp\Patterns\Dto\AbstractDto;
use App\Modules\ModuleExample\Dto\CreateResourceExampleDto;

class UpdateResourceExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateResourceExampleDto::class);
        $this->disableRequiredValues();

        return [
            'title' => ['string|required', 'The title of the post'],
            'body' => ['string|required', 'The body of the post'],
            'is_featured' => ['bool', 'Is this post featured?'],
            'password' => ['string', 'The password to view this post'],
            'post_type' => ['alpha_num', 'The type of post'],
            'status' => ['alpha_num', 'The status of the post'],
        ];
    }
}