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
        $transform['id'] = $model->id;
        $transform['title'] = $model->title;
        $transform['body'] = $model->body;
        $transform['is_featured'] = $model->is_featured;
        $transform['published_at'] = $model->published_at;
        $transform['password'] = $model->password;
        $transform['post_type'] = $model->post_type;
        $transform['status'] = $model->status;
        $transform['created_by'] = $model->created_by;
        $transform['created_at'] = $model->created_at;
        $transform['updated_at'] = $model->updated_at;

        return $transform;
    }
}
