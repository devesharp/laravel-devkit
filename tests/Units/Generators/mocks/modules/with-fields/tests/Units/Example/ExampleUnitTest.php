<?php

namespace Tests\Units\Example;

use App\Modules\Example\Supports\Docs\ExampleRouteDoc;
use App\Modules\Example\Dtos\CreateExampleDto;
use App\Modules\Example\Dtos\SearchExampleDto;
use App\Modules\Example\Dtos\UpdateExampleDto;
use App\Modules\Example\Resources\Models\Example;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Example\Services\ExampleService;

use Tests\TestCase;

class ExampleUnitTest extends TestCase
{
    public ExampleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ExampleService::class);
    }

    /**
     * @testdox create - default
     */
    public function testCreateExample()
    {
        $user = Users::factory()->create();
        $resourceData = Example::factory()->raw();

        $resource = $this->service->create(CreateExampleDto::make($resourceData), $user, 'default');

        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertSame($resourceData['title'], $resource['title'], 'title');
        $this->assertSame($resourceData['body'], $resource['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $resource['is_featured'], 'is_featured');
        $this->assertDateEqual($resourceData['published_at'], $resource['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $resource['password'], 'password');
        $this->assertSame($resourceData['post_type'], $resource['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $resource['status'], 'status');
        $this->assertSame($resourceData['created_by'], $resource['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($resource['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($resource['updated_at'], 'updated_at');
    }

    /**
     * @testdox update - default
     */
    public function testUpdateExample()
    {
        $user = Users::factory()->create();
        $resource = Example::factory()->create();
        $resourceData = Example::factory()->raw();

        $resource = $this->service->update($resource['id'], UpdateExampleDto::make($resourceData), $user, 'default');

        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertSame($resourceData['title'], $resource['title'], 'title');
        $this->assertSame($resourceData['body'], $resource['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $resource['is_featured'], 'is_featured');
        $this->assertDateEqual($resourceData['published_at'], $resource['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $resource['password'], 'password');
        $this->assertSame($resourceData['post_type'], $resource['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $resource['status'], 'status');
        $this->assertSame($resourceData['created_by'], $resource['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($resource['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($resource['updated_at'], 'updated_at');
    }

    /**
     * @testdox get - default
     */
    public function testGetExample()
    {
        $user = Users::factory()->create();
        $resourceOther = Example::factory()->create();
        $resource = Example::factory()->create();

        $resourceGet = $this->service->get($resource->id, $user);

        $this->assertGreaterThanOrEqual($resource->id, $resourceGet['id']);
        $this->assertSame($resource->title, $resourceGet['title'], 'title');
        $this->assertSame($resource->body, $resourceGet['body'], 'body');
        $this->assertSame($resource->is_featured, $resourceGet['is_featured'], 'is_featured');
        $this->assertDateEqual($resource->published_at, $resourceGet['published_at'], 'published_at');
        $this->assertSame($resource->password, $resourceGet['password'], 'password');
        $this->assertSame($resource->post_type, $resourceGet['post_type'], 'post_type');
        $this->assertSame($resource->status, $resourceGet['status'], 'status');
        $this->assertSame($resource->created_by, $resourceGet['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($resourceGet['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($resourceGet['updated_at'], 'updated_at');
    }

    /**
     * @testdox search - default
     */
    public function testSearchExample()
    {
        $user = Users::factory()->create();
        Example::factory()->count(5)->create();

        $results = $this->service->search(SearchExampleDto::make([
            "filters" => [
                "id" => 1
            ]
        ]), $user);
        $this->assertEquals(1, $results['count']);
    }

    /**
     * @testdox delete - default
     */
    public function testDeleteExample()
    {
        $user = Users::factory()->create();
        $resourceData = Example::factory()->raw();

        $resource = $this->service->create(CreateExampleDto::make($resourceData), $user);

        $this->service->delete($resource['id'], $user);

        $this->assertFalse(!!Example::query()->find($resource['id'])->enabled);
    }
}
