<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Illuminate\Database\Eloquent\Model;

class RepositoryFoo2Stub extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = Foo2Stub::class;

    protected $noEnabled = false;

    static $id = 0;

    public function findMany($enabled = true)
    {
        return [
            (object) [
                'id' => 1,
                'alternative_id' => 4,
                'login' => 'john.' . 0
            ],
            (object) [
                'id' => 2,
                'alternative_id' => 5,
                'login' => 'john.' . 1
            ],
            (object) [
                'id' => 3,
                'alternative_id' => 6,
                'login' => 'john.' . 2
            ],
        ];
    }
}
