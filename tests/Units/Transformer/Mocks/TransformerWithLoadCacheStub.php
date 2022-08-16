<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\CRUD\Transformer;

class TransformerWithLoadCacheStub extends Transformer
{
    public string $model = ModelStub::class;

    protected array $loads = [
        // Nome => Repositorio, localKey, foreignKey (default: id)
        'foo' => [RepositoryFooStub::class, 'user_create'],
        // Nome => Repositorio, localKey, foreignKey (default: id)
        'alternative' => [RepositoryFoo2Stub::class, 'user_create', 'alternative_id'],
    ];

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
        $transform = $model->toArray();

        $transform['user_create'] = $this->getFoo($transform['user_create'])->login;
        $transform['updated_at'] = (string) $model->updated_at;
        $transform['created_at'] = (string) $model->created_at;

        return $transform;
    }

    public function transformCustom($model, $requester = null, $default = [])
    {
        $transform = $model->toArray();

        $default['user_edit'] = $this->getAlternative($transform['user_edit'])->login ?? '';

        return $default;
    }
}
