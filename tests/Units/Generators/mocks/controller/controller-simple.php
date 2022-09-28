<?php

namespace App\Modules\Products\Dtos;

use Devesharp\Patterns\Controller\ControllerBase;
use App\Modules\Products\Dtos\CreateEletronicsDto;
use App\Modules\Products\Dtos\SearchEletronicsDto;
use App\Modules\Products\Dtos\UpdateEletronicsDto;

class EletronicsDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
        ];
    }
}