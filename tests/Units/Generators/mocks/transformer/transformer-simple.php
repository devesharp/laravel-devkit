<?php

namespace App\Modules\Products\Transformers;

use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\Products\Resources\Models\Eletronics;

class EletronicsTransformer extends Transformer
{
    public $model = Eletronics::class;

    protected array $loads = [];

    /**
     * @param $model
     * @param string $context
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function transformDefault(
        $model,
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }

        $transform = [];

        return $transform;
    }
}
