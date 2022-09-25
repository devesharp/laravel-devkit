<?php

namespace Tests\Routes\Example;

use App\Modules\Example\Supports\DocsExampleRouteDoc;
use App\Modules\Example\Dtos\CreateExampleDto;
use App\Modules\Example\Dtos\SearchExampleDto;
use App\Modules\Example\Dtos\UpdateExampleDto;
use App\Modules\Example\Resources\Models\Example;
use App\Modules\Users\Resources\Models\Users;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ExampleRouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/example
     */
    public function testRouteExampleCreate()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = Example::factory()->raw();

        $response = $this->withPost('/v1/example')
            ->setRouteInfo('CreateExample', ExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Example'])
            ->addBody($resourceData, CreateExampleDto::class)
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
     * @testdox [POST] /v1/example/:id
     */
    public function testRouteExampleUpdate()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resourceData = Example::factory()->raw();
        $resource = Example::factory()->create();

        $response = $this->withPost('/v1/example/:id')
            ->addPath('id', $resource->id, 'ID do Example')
            ->setRouteInfo('UpdateExample', ExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Example'])
            ->addBody($resourceData, UpdateExampleDto::class)
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
     * @testdox [GET] /v1/example/:id
     */
    public function testRouteExampleGet()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        $resource = Example::factory()->create();

        $response = $this->withGet('/v1/example/:id')
            ->addPath('id', $resource->id, 'ID do Example')
            ->setRouteInfo('GetExample', ExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Example'])
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
     * @testdox [POST] /v1/example/search
     */
    public function testRouteExampleSearch()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);
        Example::factory()->count(3)->create();

        $response = $this->withPost('/v1/example/search')
            ->setRouteInfo('SearchExample', ExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Example'])
            ->addBody([
                'filters' => [
                    'id' => 1
                ]
            ], SearchExampleDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertEquals(1, $responseData['data']['count']);
        $this->assertEquals(1, count($responseData['data']['results']));
    }

    /**
     * @testdox [DELETE] /v1/example/:id
     */
    public function testRouteExampleDelete()
    {
        $user = Users::factory()->create();
        $user->access_token = JWTAuth::fromUser($user);

        $resource = Example::factory()->create();

        $response = $this->withDelete('/v1/example/:id')
            ->addPath('id', $resource->id, 'Id do Example')
            ->setRouteInfo('DeleteExample', ExampleRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Example'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertTrue(!!$responseData['data']);
    }
}
