<?php

namespace App\Modules\Example\Resources\Repositories;

use Devesharp\Patterns\Repository\RepositoryMysql;

/**
 * Class ExampleRepository
 *
 * @method public                                      Builder getModel()
 * @method \App\Modules\Example\Resources\Models\Example findById($id, $enabled = true)
 * @method \App\Modules\Example\Resources\Models\Example findIdOrFail($id, $enabled = true)
 */
class ExampleRepository extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = \App\Modules\Example\Resources\Models\Example::class;
}
