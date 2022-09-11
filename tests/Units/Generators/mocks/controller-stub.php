"<?php

namespace App\Modules\ModuleExample\Controller;

use Devesharp\Patterns\Controller\ControllerBase;
use App\Modules\ModuleExample\Dto\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dto\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dto\UpdateResourceExampleDto;

class ResourceExampleController extends ControllerBase
{
    public function __construct(
        protected App\Modules\ModuleExample\Service\ResourceExampleService $service
    ) {
        parent::__construct();
    }

    public function search()
    {
        return $this->service->search(SearchResourceExampleDto::make(request()->all()), $this->auth, 'default');
    }

    public function get($id)
    {
        return $this->service->get($id, $this->auth);
    }

    public function update($id)
    {
        return $this->service->update($id, UpdateResourceExampleDto::make(request()->all()), $this->auth, 'default');
    }

    public function create()
    {
        return $this->service->create(CreateResourceExampleDto::make(request()->all()), $this->auth, 'default');
    }

    public function delete($id)
    {
        return $this->service->delete($id, $this->auth);
    }
}