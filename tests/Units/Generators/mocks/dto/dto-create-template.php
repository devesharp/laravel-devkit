<?php

namespace App\Modules\Posts\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use Devesharp\Patterns\Dto\Templates\SearchTemplateDto;

class SearchPostsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(SearchTemplateDto::class);

        return [
            'filters.id' => new Rule('numeric_string', 'The ID of the post'),
            'filters.platform_id' => new Rule('string', 'Platform ID'),
            'filters.user_id' => new Rule('string', 'The user who created the post'),
            'filters.title' => new Rule('string', 'The title of the post'),
            'filters.body' => new Rule('string', 'The body of the post'),
            'filters.is_featured' => new Rule('bool', 'Is this post featured?'),
            'filters.published_at' => new Rule('string', 'When was this post published?'),
            'filters.password' => new Rule('string', 'The password to view this post'),
            'filters.post_type' => new Rule('numeric_string', 'The type of post'),
            'filters.status' => new Rule('numeric_string', 'The status of the post'),
            'filters.created_by' => new Rule('string|min:1', 'The user that created this post'),
            'filters.created_at' => new Rule('string', 'When was this post created?'),
            'filters.updated_at' => new Rule('string', 'When was this post updated?'),
        ];
    }
}