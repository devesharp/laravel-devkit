<?php

namespace App\Modules\Products\Resources\Controllers;

use Devesharp\Patterns\Controller\ControllerBase;
use App\Modules\Products\Dtos\CreateEletronicsDto;
use App\Modules\Products\Dtos\SearchEletronicsDto;
use App\Modules\Products\Dtos\UpdateEletronicsDto;

class EletronicsController extends ControllerBase
{
    public function __construct(
        protected \App\Modules\Products\Services\EletronicsService $service
    ) {
        parent::__construct();
    }

    public function search()
    {
        return $this->service->search(SearchEletronicsDto::make(request()->all()), $this->auth, 'default');
    }

    public function get($id)
    {
        return $this->service->get($id, $this->auth);
    }

    public function update($id)
    {
        return $this->service->update($id, UpdateEletronicsDto::make(request()->all()), $this->auth, 'default');
    }

    public function create()
    {
        return $this->service->create(CreateEletronicsDto::make(request()->all()), $this->auth, 'default');
    }

    public function delete($id)
    {
        return $this->service->delete($id, $this->auth);
    }
}
