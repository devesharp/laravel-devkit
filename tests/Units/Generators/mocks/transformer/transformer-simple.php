<?php

namespace App\Modules\ModuleExample\Transformer;

use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\ModuleExample\Resources\Model\ResourceExample;

class ResourceExampleTransformer extends Transformer
{
    public $model = ResourceExample::class;

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
