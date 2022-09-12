<?php

namespace Tests\Units\ModuleExample;

use App\Modules\ModuleExample\Supports\Docs\ResourceExampleRouteDoc;
use App\Modules\ModuleExample\Dto\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dto\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dto\UpdateResourceExampleDto;
use App\Modules\ModuleExample\Resources\Model\ResourceExample;
use App\Modules\ModuleExample\Resources\Model\Users;
use App\Modules\ModuleExample\Service\ResourceExampleService;
use App\Modules\ModuleExample\Resources\Model\Platforms;
use App\Modules\ModuleExample\Resources\Model\UsersTypes;

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
        $this->assertSame($resourceData['title'], $resource['title']);
        $this->assertSame($resourceData['body'], $resource['body']);
        $this->assertSame($resourceData['is_featured'], $resource['is_featured']);
        $this->assertSame($resourceData['published_at'], $resource['published_at']);
        $this->assertSame($resourceData['password'], $resource['password']);
        $this->assertSame($resourceData['post_type'], $resource['post_type']);
        $this->assertSame($resourceData['status'], $resource['status']);
        $this->assertSame($resourceData['created_by'], $resource['created_by']);
        $this->assertSame($resourceData['created_at'], $resource['created_at']);
        $this->assertSame($resourceData['updated_at'], $resource['updated_at']);
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
        $this->assertSame($resourceData['title'], $resource['title']);
        $this->assertSame($resourceData['body'], $resource['body']);
        $this->assertSame($resourceData['is_featured'], $resource['is_featured']);
        $this->assertSame($resourceData['published_at'], $resource['published_at']);
        $this->assertSame($resourceData['password'], $resource['password']);
        $this->assertSame($resourceData['post_type'], $resource['post_type']);
        $this->assertSame($resourceData['status'], $resource['status']);
        $this->assertSame($resourceData['created_by'], $resource['created_by']);
        $this->assertSame($resourceData['created_at'], $resource['created_at']);
        $this->assertSame($resourceData['updated_at'], $resource['updated_at']);
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
        $resource = ResourceExample::factory()->create();

        $resource = $this->service->get($resource->id, $user);

        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertSame($resource->title, $resource['title']);
        $this->assertSame($resource->body, $resource['body']);
        $this->assertSame($resource->is_featured, $resource['is_featured']);
        $this->assertSame($resource->published_at, $resource['published_at']);
        $this->assertSame($resource->password, $resource['password']);
        $this->assertSame($resource->post_type, $resource['post_type']);
        $this->assertSame($resource->status, $resource['status']);
        $this->assertSame($resource->created_by, $resource['created_by']);
        $this->assertSame($resource->created_at, $resource['created_at']);
        $this->assertSame($resource->updated_at, $resource['updated_at']);
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
