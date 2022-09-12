<?php

namespace App\Modules\ModuleExample\Resources\Repository;

use Devesharp\Patterns\Repository\RepositoryMysql;

/**
 * Class ResourceExampleRepository
 *
 * @method public                                      Builder getModel()
 * @method App\Modules\ModuleExample\Resources\Model\ResourceExample findById($id, $enabled = true)
 * @method App\Modules\ModuleExample\Resources\Model\ResourceExample findIdOrFail($id, $enabled = true)
 */
class ResourceExampleRepository extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = App\Modules\ModuleExample\Resources\Model\ResourceExample::class;
}