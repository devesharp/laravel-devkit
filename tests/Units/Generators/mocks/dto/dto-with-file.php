<?php

namespace App\Modules\Products\Dtos;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;

class EletronicsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'category_id' => new Rule('numeric_string', 'The category of the product'),
            'title' => new Rule('string|required', 'The title of the post'),
            'body' => new Rule('string|required', 'The body of the post'),
            'is_featured' => new Rule('bool', 'Is this post featured?'),
            'password' => new Rule('string', 'The password to view this post'),
            'post_type' => new Rule('numeric_string', 'The type of post'),
            'status' => new Rule('numeric_string', 'The status of the post'),
        ];
    }
}