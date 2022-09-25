<?php

namespace App\Modules\Example\Dtos;

use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Templates\SearchTemplateDto;

class SearchExampleDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(SearchTemplateDto::class);

        return [
            'filters.id' => ['numeric_string', 'The ID of the post'],
            'filters.platform_id' => ['string', 'Platform ID'],
            'filters.user_id' => ['string', 'The user who created the post'],
            'filters.title' => ['string', 'The title of the post'],
            'filters.body' => ['string', 'The body of the post'],
            'filters.is_featured' => ['bool', 'Is this post featured?'],
            'filters.published_at' => ['string', 'When was this post published?'],
            'filters.password' => ['string', 'The password to view this post'],
            'filters.post_type' => ['numeric_string', 'The type of post'],
            'filters.status' => ['numeric_string', 'The status of the post'],
            'filters.created_by' => ['string|min:1', 'The user that created this post'],
            'filters.created_at' => ['string', 'When was this post created?'],
            'filters.updated_at' => ['string', 'When was this post updated?'],
        ];
    }
}