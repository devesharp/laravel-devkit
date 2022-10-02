<?php

namespace App\Modules\Products\Dtos;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Dto\Rule;
use App\Modules\Products\Dtos\CreateEletronicsDto;

class UpdateEletronicsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(CreateEletronicsDto::class);
        $this->disableRequiredValues();

        return [
        ];
    }
}