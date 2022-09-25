<?php

namespace App\Modules\Example\Transformers;

use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\Example\Resources\Models\Example;

class ExampleTransformer extends Transformer
{
    public $model = Example::class;

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
        $transform['id'] = $model->id;
        $transform['title'] = $model->title;
        $transform['body'] = $model->body;
        $transform['is_featured'] = (bool) $model->is_featured;
        $transform['published_at'] = $model->published_at;
        $transform['password'] = $model->password;
        $transform['post_type'] = (float) $model->post_type;
        $transform['status'] = (float) $model->status;
        $transform['created_by'] = $model->created_by;
        $transform['created_at'] = $model->created_at;
        $transform['updated_at'] = $model->updated_at;

        return $transform;
    }
}
