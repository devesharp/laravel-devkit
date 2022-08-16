<?php

namespace Tests\Units\Service\Mocks;

use Devesharp\Patterns\Repository\RepositoryMysql;

class RepositoryStub extends RepositoryMysql
{
    /**
     * @var string
     */
    protected $model = ModelStub::class;

    protected bool $disableEnabledColumn = true;

    protected $softDelete = false;
}
