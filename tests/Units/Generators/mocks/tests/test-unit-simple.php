<?php

namespace Tests\Units\ModuleExample;

use App\Modules\ModuleExample\Supports\Docs\ResourceExampleRouteDoc;
use App\Modules\ModuleExample\Dtos\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dtos\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dtos\UpdateResourceExampleDto;
use App\Modules\ModuleExample\Resources\Models\ResourceExample;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\ModuleExample\Services\ResourceExampleService;
use App\Modules\ModuleExample\Resources\Models\Platforms;
use App\Modules\ModuleExample\Resources\Models\UsersTypes;

use Tests\TestCase;

class ResourceExampleUnitTest extends TestCase
{
    public ResourceExampleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ResourceExampleService::class);
    }

    /**
     * @testdox create - default
     */
    public function testCreateResourceExample()
    {
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();
        $resourceData = ResourceExample::factory()->raw();

        $resource = $this->service->create(CreateResourceExampleDto::make($resourceData), $user, 'default');

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
    public function testUpdateResourceExample()
    {
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();
        $resource = ResourceExample::factory()->create();
        $resourceData = ResourceExample::factory()->raw();

        $resource = $this->service->update($resource['id'], UpdateResourceExampleDto::make($resourceData), $user, 'default');

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
    public function testGetResourceExample()
    {
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();
        $resourceOther = ResourceExample::factory()->create();
        $resource = ResourceExample::factory()->create();

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
    public function testSearchResourceExample()
    {
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();
        ResourceExample::factory()->count(5)->create();

        $results = $this->service->search(SearchResourceExampleDto::make([
            "filters" => [
                "id" => 1
            ]
        ]), $user);
        $this->assertEquals(1, $results['count']);
    }

    /**
     * @testdox delete - default
     */
    public function testDeleteResourceExample()
    {
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();
        $resourceData = ResourceExample::factory()->raw();

        $resource = $this->service->create(CreateResourceExampleDto::make($resourceData), $user);

        $this->service->delete($resource['id'], $user);

        $this->assertFalse(!!ResourceExample::query()->find($resource['id'])->enabled);
    }
}
