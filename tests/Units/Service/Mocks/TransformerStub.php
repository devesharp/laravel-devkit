<?php

namespace Tests\Units\Service\Mocks;

use Devesharp\Patterns\Transformer\Transformer;

class TransformerStub extends Transformer
{
    public string $model = ModelStub::class;

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

        $transform = $model->toArray();

        $transform['updated_at'] = (string) $model->updated_at;
        $transform['created_at'] = (string) $model->created_at;

        return $transform;
    }
}
