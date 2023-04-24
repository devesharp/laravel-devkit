<?php

namespace App\Modules\Products\Resources\Repositories;

use Devesharp\Patterns\Repository\RepositoryMysql;

/**
 * Class EletronicsRepository
 *
 * @method public                                      Builder getModel()
 * @method \App\Modules\Products\Resources\Models\Eletronics findById($id, $enabled = true)
 * @method \App\Modules\Products\Resources\Models\Eletronics findIdOrFail($id, $enabled = true)
 */
class EletronicsRepository extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = \App\Modules\Products\Resources\Models\Eletronics::class;
}
