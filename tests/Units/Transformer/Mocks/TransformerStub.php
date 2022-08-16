<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\CRUD\Transformer;

class TransformerStub extends Transformer
{
    public string $model = ModelStub::class;

    public function transformDefault(
        $model,
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }

        return [
            'id' => $model->id,
            'name' => $model->name,
            'age' => $model->age,
            'user_create' => $model->user_create,
            'updated_at' => (string) $model->updated_at,
            'created_at' => (string) $model->created_at,
        ];
    }

    public function transformCustom($context, $requester = null, $default = [])
    {
        $default['custom'] = true;

        return $default;
    }

    // Repository Mock
    public function loadFoo(array $users)
    {
        $this->loadResource('foo', app(RepositoryFooStub::class), $users);
    }
}
