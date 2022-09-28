<?php

namespace App\Modules\Posts\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class PostsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'title' => new Rule('string|required', 'The title of the post'),
            'body' => new Rule('string|required', 'The body of the post'),
            'is_featured' => new Rule('bool', 'Is this post featured?'),
            'password' => new Rule('string', 'The password to view this post'),
            'post_type' => new Rule('numeric_string', 'The type of post'),
            'status' => new Rule('numeric_string', 'The status of the post'),
        ];
    }
}