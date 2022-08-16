<?php

namespace Tests\Units\Service;

use Devesharp\Support\Helpers;
use Illuminate\Support\Arr;
use Tests\Units\Service\Mocks\ModelStub;
use Tests\Units\Service\Mocks\ServiceStub;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    public ServiceStub $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ServiceStub::class);
    }

    /**
     * @testdox Service - create
     */
    public function testCreateService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        $this->assertEquals(Helpers::arrayExclude($model, ['created_at', 'updated_at']), [
            'id' => 1,
            'name' => 'John',
            'user_create' => 0,
            'age' => 10
        ]);
    }

    /**
     * @testdox Service - update
     */
    public function testUpdateService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        $model = $this->service->update($model['id'], [
            'name' => 'John Wick',
            'age' => 11,
            'extends' => true
        ]);

        $this->assertEquals(Helpers::arrayExclude($model, ['created_at', 'updated_at']), [
            'id' => 1,
            'name' => 'John Wick',
            'user_create' => 0,
            'age' => 11
        ]);
    }

    /**
     * @testdox Service - search
     */
    public function testSearchService()
    {
        $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Veronica',
            'age' => 12,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Woo Lipters',
            'age' => 80,
            'extends' => true
        ]);
        $this->service->create([
            'name' => 'Willy John',
            'age' => 21,
            'extends' => true
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->search([
            'filters' => [
                'name' => 'john'
            ]
        ]);
        $this->assertEquals(Arr::pluck($result['results'], 'id'), [1, 4]);
        $this->assertEquals($result['count'], 2);

        /**
         * Buscar full_name raw
         */
        $result = $this->service->search([
            'filters' => [
                'full_name' => 'john 21'
            ]
        ]);

        $this->assertEquals(Arr::pluck($result['results'], 'id'), [4]);
        $this->assertEquals($result['count'], 1);
    }

    /**
     * @testdox Service - delete
     */
    public function testDeleteService()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10,
            'extends' => true
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->delete($model['id']);

        $this->assertEquals(true, $result);

        $this->assertEquals(null, ModelStub::query()->first());
    }

    /**
     * @testdox filterSearch - default with empty
     */
    public function testFilterSearch()
    {
        $model = $this->service->filterSearch([], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - sort asc
     */
    public function testFilterSearchSortAsc()
    {
        // defining sorts allowed
        $this->service->sort = [
            'name' => [
                'column' => 'name',
            ],
        ];

        $model = $this->service->filterSearch([
            'query' => [
                'sort' => 'name'
            ]
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "name" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - sort desc
     */
    public function testFilterSearchSortDesc()
    {
        // defining sorts allowed
        $this->service->sort = [
            'name' => [
                'column' => 'name',
            ],
        ];
        $model = $this->service->filterSearch([
            'query' => [
                'sort' => '-name'
            ]
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "name" desc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - many sort
     */
    public function testFilterSearchManySort()
    {
        // defining sorts allowed
        $this->service->sort = [
            'id' => [
                'column' => 'id',
            ],
            'name' => [
                'column' => 'name',
            ],
            'age' => [
                'column' => 'age',
            ],
        ];

        $model = $this->service->filterSearch([
            'query' => [
                'sort' => 'name,-id,-age'
            ]
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "name" asc, "id" desc, "age" desc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - limit
     */
    public function testFilterSearchLimit()
    {
        // defining sorts allowed
        $this->service->limitMax = 40;

        $model = $this->service->filterSearch([
            'query' => [
                'limit' => 30
            ],
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "id" asc limit 30', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - page
     */
    public function testFilterSearchPage()
    {
        // defining sorts allowed
        $this->service->limitMax = 40;
        $this->service->limitDefault = 40;

        $model = $this->service->filterSearch([
            'query' => [
                'page' => 2
            ],
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        // page * limit
        $this->assertEquals('select * from "model_stubs" order by "id" asc limit 40 offset 40', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - offset
     */
    public function testFilterSearchOffset()
    {
        // defining sorts allowed
        $this->service->limitMax = 40;

        $model = $this->service->filterSearch([
            'query' => [
                'offset' => 30
            ],
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" order by "id" asc limit 20 offset 30', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - filters
     */
    public function testFilterSearchFilters()
    {
        $model = $this->service->filterSearch([
            'filters' => [
                'id' => 30
            ],
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" where "id" = ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox filterSearch - filters with raw
     */
    public function testFilterSearchFiltersRaw()
    {
        $model = $this->service->filterSearch([
            'filters' => [
                'full_name' => 'sdsd'
            ],
        ], app(\Tests\Units\Service\Mocks\RepositoryStub::class));

        $this->assertEquals('select * from "model_stubs" where (name || \' \' || age) LIKE ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox makeSelectActions - with number
     */
    public function testMakeSelectInt()
    {
        $model = $this->service->makeSelectActions(1);

        $this->assertEquals('select * from "model_stubs" where "model_stubs"."id" = ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox makeSelectActions - with array numbers
     */
    public function testMakeSelectArray()
    {
        $model = $this->service->makeSelectActions([1,2,3]);

        $this->assertEquals('select * from "model_stubs" where "model_stubs"."id" in (?, ?, ?) order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox makeSelectActions - with makeSearch
     */
    public function testMakeSelectFilter()
    {
        $model = $this->service->makeSelectActions([
            'id' => 30,
            'full_name' => 'sdsd'
        ]);

        $this->assertEquals('select * from "model_stubs" where "id" = ? and (name || \' \' || age) LIKE ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }
}
