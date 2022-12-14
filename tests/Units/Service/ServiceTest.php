<?php

namespace Tests\Units\Service;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
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
            'age' => 10
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
            'age' => 10
        ]);

        $model = $this->service->update($model['id'], [
            'name' => 'John Wick',
            'age' => 11
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
            'age' => 10
        ]);
        $this->service->create([
            'name' => 'Veronica',
            'age' => 12
        ]);
        $this->service->create([
            'name' => 'Woo Lipters',
            'age' => 80
        ]);
        $this->service->create([
            'name' => 'Willy John',
            'age' => 21
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
            'age' => 10
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->delete($model['id']);

        $this->assertEquals(true, $result);

        $this->assertEquals(null, ModelStub::query()->first());
    }

    /**
     * @testdox Service - delete with one Id
     */
    public function testDeleteServiceMany()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model2 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->deleteMany([
            'id' => $model['id'],
        ]);

        $this->assertEquals(true, $result);

        $this->assertNull(ModelStub::find($model['id']));
        $this->assertNotNull(ModelStub::find($model2['id']));
    }

    /**
     * @testdox Service - delete with one Ids
     */
    public function testDeleteServiceManyIds()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model2 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model3 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);

        /**
         * Buscar name
         */
        $result = $this->service->deleteMany([
            'ids' => [
                $model['id'],
                $model3['id'],
            ],
        ]);

        $this->assertEquals(true, $result);

        $this->assertNull(ModelStub::find($model['id']));
        $this->assertNotNull(ModelStub::find($model2['id']));
        $this->assertNull(ModelStub::find($model3['id']));
    }

    /**
     * @testdox Service - delete with filters
     */
    public function testDeleteServiceManyFilters()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model2 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model3 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);

        $result = $this->service->deleteMany([
            'filters' => [
                'id' => $model['id'],
            ],
        ]);

        $this->assertEquals(true, $result);

        $this->assertNull(ModelStub::find($model['id']));
        $this->assertNotNull(ModelStub::find($model2['id']));
        $this->assertNotNull(ModelStub::find($model3['id']));
    }

    /**
     * @testdox Service - delete with filters not be empty
     */
    public function testDeleteServiceManyFiltersNotEmpty()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(Exception::SEARCH_FILTERS_EMPTY);

        $model = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model2 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model3 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);

        $result = $this->service->deleteMany(new Collection([
            'filters' => [],
        ]));
    }

    /**
     * @testdox Service - delete all resource with filters,
     */
    public function testDeleteServiceManyFiltersNotEmptyActionAll()
    {
        $model = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model2 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);
        $model3 = $this->service->create([
            'name' => 'John',
            'age' => 10
        ]);

        $result = $this->service->deleteMany(new Collection([
            'filters' => [],
            'enable_select_all' => true,
        ]));

        $this->assertNull(ModelStub::find($model['id']));
        $this->assertNull(ModelStub::find($model2['id']));
        $this->assertNull(ModelStub::find($model3['id']));
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
        $model = $this->service->makeSelectActions([
            'id' => 1
        ]);

        $this->assertEquals('select * from "model_stubs" where "model_stubs"."id" = ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox makeSelectActions - with array numbers
     */
    public function testMakeSelectArray()
    {
        $model = $this->service->makeSelectActions([
            'ids' => [1,2,3]
        ]);

        $this->assertEquals('select * from "model_stubs" where "model_stubs"."id" in (?, ?, ?) order by "id" asc limit 20', $model->getBuilder()->toSql());
    }

    /**
     * @testdox makeSelectActions - with makeSearch
     */
    public function testMakeSelectFilter()
    {
        $model = $this->service->makeSelectActions([
            'filters' => [
                'id' => 30,
                'full_name' => 'sdsd'
            ]
        ]);

        $this->assertEquals('select * from "model_stubs" where "id" = ? and (name || \' \' || age) LIKE ? order by "id" asc limit 20', $model->getBuilder()->toSql());
    }
}
