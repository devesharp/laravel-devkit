<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Illuminate\Database\Eloquent\Model;

class RepositoryFooStub extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = FooStub::class;

    protected $noEnabled = false;

    static $id = 0;

    public function findMany($enabled = true)
    {
        return [
            (object) [
                'id' => 1,
                'login' => 'john.' . RepositoryFooStub::$id++
            ],
            (object) [
                'id' => 2,
                'login' => 'john.' . RepositoryFooStub::$id++
            ],
            (object) [
                'id' => 3,
                'login' => 'john.' . RepositoryFooStub::$id++
            ],
        ];
    }
}
