<?php

namespace App\Modules\Example\Resources\Controllers;

use Devesharp\Patterns\Controller\ControllerBase;
use App\Modules\Example\Dtos\CreateExampleDto;
use App\Modules\Example\Dtos\SearchExampleDto;
use App\Modules\Example\Dtos\UpdateExampleDto;
use App\Modules\Example\Interfaces\ExampleTransformerType;

class ExampleController extends ControllerBase
{
    public function __construct(
        protected \App\Modules\Example\Services\ExampleService $service
    ) {
        parent::__construct();
    }

    public function search()
    {
        return $this->service->search(SearchExampleDto::make(request()->all()), $this->auth, ExampleTransformerType::default );
    }

    public function get($id)
    {
        return $this->service->get($id, $this->auth);
    }

    public function update($id)
    {
        return $this->service->update($id, UpdateExampleDto::make(request()->all()), $this->auth, ExampleTransformerType::default );
    }

    public function create()
    {
        return $this->service->create(CreateExampleDto::make(request()->all()), $this->auth, ExampleTransformerType::default );
    }

    public function delete($id)
    {
        return $this->service->delete($id, $this->auth);
    }
}
