<?php

namespace Tests\Routes\Products;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use App\Modules\Products\Supports\Docs\EletronicsRouteDoc;
use App\Modules\Products\Dtos\CreateEletronicsDto;
use App\Modules\Products\Dtos\SearchEletronicsDto;
use App\Modules\Products\Dtos\UpdateEletronicsDto;
use App\Modules\Products\Resources\Models\Eletronics;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class EletronicsRouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/eletronics
     */
    public function testRouteEletronicsCreate()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);

        $cartegory = Cartegories::factory()->create();
        $resourceData = Eletronics::factory([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'category_id' => $cartegory->id,
            'created_by' => $user->id,
        ])->bodyForRequest()->raw();

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/eletronics')
            ->setRouteInfo('CreateEletronics', EletronicsRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Eletronics'])
            ->addBody($resourceData, CreateEletronicsDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resourceData['title'], $responseData['data']['title'], 'title');
        $this->assertSame($resourceData['body'], $responseData['data']['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateLessOrEqualThanNow($responseData['data']['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $responseData['data']['password'], 'password');
        $this->assertSame($resourceData['post_type'], $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $responseData['data']['status'], 'status');
        $this->assertSame($resourceData['created_by'], $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [POST] /v1/eletronics/:id
     */
    public function testRouteEletronicsUpdate()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);

        $cartegory = Cartegories::factory()->create();
        $resource = Eletronics::factory([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'category_id' => $cartegory->id,
            'created_by' => $user->id,
        ])->create();
        $resourceData = Eletronics::factory([
                'platform_id' => $platform->id,
                'user_id' => $user->id,
                'category_id' => $cartegory->id,
                'created_by' => $user->id,
        ])->bodyForRequest()->raw();


        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/eletronics/:id')
            ->addPath('id', $resource->id, 'ID do Eletronics')
            ->setRouteInfo('UpdateEletronics', EletronicsRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Eletronics'])
            ->addBody($resourceData, UpdateEletronicsDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resourceData['title'], $responseData['data']['title'], 'title');
        $this->assertSame($resourceData['body'], $responseData['data']['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateLessOrEqualThanNow($responseData['data']['published_at'], 'published_at');
        $this->assertSame($resourceData['password'], $responseData['data']['password'], 'password');
        $this->assertSame($resourceData['post_type'], $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resourceData['status'], $responseData['data']['status'], 'status');
        $this->assertSame($resourceData['created_by'], $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [GET] /v1/eletronics/:id
     */
    public function testRouteEletronicsGet()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);

        $cartegory = Cartegories::factory()->create();
        $resource = Eletronics::factory([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'category_id' => $cartegory->id,
            'created_by' => $user->id,
        ])->create();

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withGet('/v1/eletronics/:id')
            ->addPath('id', $resource->id, 'ID do Eletronics')
            ->setRouteInfo('GetEletronics', EletronicsRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Eletronics'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertGreaterThanOrEqual(1, $responseData['data']['id']);
        $this->assertSame($resource->title, $responseData['data']['title'], 'title');
        $this->assertSame($resource->body, $responseData['data']['body'], 'body');
        $this->assertSame($resource->is_featured, $responseData['data']['is_featured'], 'is_featured');
        $this->assertDateLessOrEqualThanNow($responseData['data']['published_at'], 'published_at');
        $this->assertSame($resource->password, $responseData['data']['password'], 'password');
        $this->assertSame($resource->post_type, $responseData['data']['post_type'], 'post_type');
        $this->assertSame($resource->status, $responseData['data']['status'], 'status');
        $this->assertSame($resource->created_by, $responseData['data']['created_by'], 'created_by');
        $this->assertDateLessOrEqualThanNow($responseData['data']['created_at'], 'created_at');
        $this->assertDateLessOrEqualThanNow($responseData['data']['updated_at'], 'updated_at');
    }

    /**
     * @testdox [POST] /v1/eletronics/search
     */
    public function testRouteEletronicsSearch()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();
        $user->access_token = JWTAuth::fromUser($user);

        $cartegory = Cartegories::factory()->create();
        $resource = Eletronics::factory([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'category_id' => $cartegory->id,
            'created_by' => $user->id,
        ])->count(5)->create();

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/eletronics/search')
            ->setRouteInfo('SearchEletronics', EletronicsRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Eletronics'])
            ->addBody([
                'filters' => [
                    'id' => 1
                ]
            ], SearchEletronicsDto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertEquals(1, $responseData['data']['count']);
        $this->assertEquals(1, count($responseData['data']['results']));
    }

    /**
     * @testdox [DELETE] /v1/eletronics/:id
     */
    public function testRouteEletronicsDelete()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platforms = Platforms::factory()->create();
        $usersTypes = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platforms->id,
            'type_id' => $usersTypes->id,
        ])->create();

        $cartegory = Cartegories::factory()->create();
        $resource = Eletronics::factory([
            'platform_id' => $platform->id,
            'user_id' => $user->id,
            'category_id' => $cartegory->id,
            'created_by' => $user->id,
        ])->create();

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withDelete('/v1/eletronics/:id')
            ->addPath('id', $resource->id, 'Id do Eletronics')
            ->setRouteInfo('DeleteEletronics', EletronicsRouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . $user->access_token, 'Authorization')
            ->addGroups(['Eletronics'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertTrue(!!$responseData['data']);
    }
}
