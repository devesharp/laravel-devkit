<?php

namespace Tests\Routes\ModuleExample;

use App\Modules\ModuleExample\Supports\DocsResourceExampleRouteDoc;
use App\Modules\ModuleExample\Dtos\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dtos\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dtos\UpdateResourceExampleDto;
use App\Modules\ModuleExample\Resources\Models\ResourceExample;
use App\Modules\Users\Resources\Models\Users;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ResourceExampleRouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/resource-example
     */
    public function testRouteResourceExampleCreate()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = ResourceExample::factory()->raw();

        $response = $this->withPost('/v1/resource-example')
            ->setRouteInfo('CreateResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->addBody($resourceData, CreateResourceExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resourceData['title'], $responseData['data']['title'], 'title');
        $this->assertSame($resourceData['body'], $responseData['data']['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateEqual($resourceData['published_at'], $responseData['data']['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $responseData['data']['password'], 'password');
        $this->assertSame($resourceData['post_type'], $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $responseData['data']['status'], 'status');
        $this->assertSame($resourceData['created_by'], $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [POST] /v1/resource-example/:id
     */
    public function testRouteResourceExampleUpdate()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = ResourceExample::factory()->raw();
        $resource = ResourceExample::factory()->create();

        $response = $this->withPost('/v1/resource-example/:id')
            ->addPath('id', $resource->id, 'ID do ResourceExample')
            ->setRouteInfo('UpdateResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->addBody($resourceData, UpdateResourceExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resourceData['title'], $responseData['data']['title'], 'title');
        $this->assertSame($resourceData['body'], $responseData['data']['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateEqual($resourceData['published_at'], $responseData['data']['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $responseData['data']['password'], 'password');
        $this->assertSame($resourceData['post_type'], $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $responseData['data']['status'], 'status');
        $this->assertSame($resourceData['created_by'], $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [GET] /v1/resource-example/:id
     */
    public function testRouteResourceExampleGet()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resource = ResourceExample::factory()->create();

        $response = $this->withGet('/v1/resource-example/:id')
            ->addPath('id', $resource->id, 'ID do ResourceExample')
            ->setRouteInfo('GetResourceExample', ResourceExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['ResourceExample'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resource->title, $responseData['data']['title'], 'title');
        $this->assertSame($resource->body, $responseData['data']['body'], 'body');
        $this->assertSame($resource->is_featured, $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateEqual($resource->published_at, $responseData['data']['published_at'], 'published_at');
        $this->assertSame($resource->password, $responseData['data']['password'], 'password');
        $this->assertSame($resource->post_type, $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resource->status, $responseData['data']['status'], 'status');
        $this->assertSame($resource->created_by, $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [POST] /v1/resource-example/search
     */
    public function testRouteResourceExampleSearch()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        ResourceExample::factory()->count(3)->create();

        $response = $this->withPost('/v1/resource-example/search')
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
     * @testdox [DELETE] /v1/resource-example/:id
     */
    public function testRouteResourceExampleDelete()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);

        $resource = ResourceExample::factory()->create();

        $response = $this->withDelete('/v1/resource-example/:id')
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
