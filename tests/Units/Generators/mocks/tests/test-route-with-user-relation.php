<?php

namespace Tests\Routes\ModuleExample;

use App\Modules\ModuleExample\Supports\DocsResourceExampleRouteDoc;
use App\Modules\ModuleExample\Dto\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dto\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dto\UpdateResourceExampleDto;
use App\Modules\ModuleExample\Resources\Model\ResourceExample;
use App\Modules\ModuleExample\Resources\Model\Users;
use App\Modules\ModuleExample\Resources\Model\Platforms;
use App\Modules\ModuleExample\Resources\Model\UsersTypes;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ResourceExampleRouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/ResourceExample
     */
    public function testRouteResourceExampleCreate()
    {
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = ResourceExample::factory()->raw();

        $response = $this->withPost('/v1/ResourceExample')
            ->setRouteInfo('CreateResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->addBody($resourceData, CreateResourceExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['id']);
        $this->assertSame($resourceData['title'], $responseData['title']);
        $this->assertSame($resourceData['body'], $responseData['body']);
        $this->assertSame($resourceData['is_featured'], $responseData['is_featured']);
        $this->assertSame($resourceData['published_at'], $responseData['published_at']);
        $this->assertSame($resourceData['password'], $responseData['password']);
        $this->assertSame($resourceData['post_type'], $responseData['post_type']);
        $this->assertSame($resourceData['status'], $responseData['status']);
        $this->assertSame($resourceData['created_by'], $responseData['created_by']);
        $this->assertSame($resourceData['created_at'], $responseData['created_at']);
        $this->assertSame($resourceData['updated_at'], $responseData['updated_at']);
    }

    /**
     * @testdox [POST] /v1/ResourceExample/:id
     */
    public function testRouteResourceExampleUpdate()
    {
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = ResourceExample::factory()->raw();
        $resource = ResourceExample::factory()->create();

        $response = $this->withPost('/v1/ResourceExample/:id')
            ->addPath('id', $resource->id, 'ID do ResourceExample')
            ->setRouteInfo('UpdateResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->addBody($resourceData, UpdateResourceExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['id']);
        $this->assertSame($resourceData['title'], $responseData['title']);
        $this->assertSame($resourceData['body'], $responseData['body']);
        $this->assertSame($resourceData['is_featured'], $responseData['is_featured']);
        $this->assertSame($resourceData['published_at'], $responseData['published_at']);
        $this->assertSame($resourceData['password'], $responseData['password']);
        $this->assertSame($resourceData['post_type'], $responseData['post_type']);
        $this->assertSame($resourceData['status'], $responseData['status']);
        $this->assertSame($resourceData['created_by'], $responseData['created_by']);
        $this->assertSame($resourceData['created_at'], $responseData['created_at']);
        $this->assertSame($resourceData['updated_at'], $responseData['updated_at']);
    }

    /**
     * @testdox [GET] /v1/ResourceExample/:id
     */
    public function testRouteResourceExampleGet()
    {
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resource = ResourceExample::factory()->create();

        $response = $this->withGet('/v1/ResourceExample/:id')
            ->addPath('id', $resource->id, 'ID do ResourceExample')
            ->setRouteInfo('GetResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['id']);
        $this->assertSame($resource->title, $responseData['title']);
        $this->assertSame($resource->body, $responseData['body']);
        $this->assertSame($resource->is_featured, $responseData['is_featured']);
        $this->assertSame($resource->published_at, $responseData['published_at']);
        $this->assertSame($resource->password, $responseData['password']);
        $this->assertSame($resource->post_type, $responseData['post_type']);
        $this->assertSame($resource->status, $responseData['status']);
        $this->assertSame($resource->created_by, $responseData['created_by']);
        $this->assertSame($resource->created_at, $responseData['created_at']);
        $this->assertSame($resource->updated_at, $responseData['updated_at']);
    }

    /**
     * @testdox [POST] /v1/ResourceExample/search
     */
    public function testRouteResourceExampleSearch()
    {
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);
        ResourceExample::factory()->count(3)->create();

        $response = $this->withPost('/v1/ResourceExample/search')
            ->setRouteInfo('SearchResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->addBody([
                'filters' => [
                    'id' => 1
                ]
            ], SearchResourceExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertEquals(1, $responseData['data']['count']);
        $this->assertEquals(1, count($responseData['data']['results']));
    }

    /**
     * @testdox [DELETE] /v1/ResourceExample/:id
     */
    public function testRouteResourceExampleDelete()
    {
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);

        $resource = ResourceExample::factory()->create();

        $response = $this->withDelete('/v1/ResourceExample/:id')
            ->addPath('id', $resource->id, 'Id do ResourceExample')
            ->setRouteInfo('DeleteResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertTrue(!!$responseData['data']);
    }
}
