<?php

namespace Tests\Units\Products;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use App\Modules\Products\Dtos\CreateEletronicsDto;
use App\Modules\Products\Dtos\SearchEletronicsDto;
use App\Modules\Products\Dtos\UpdateEletronicsDto;
use App\Modules\Products\Resources\Models\Eletronics;
use App\Modules\Products\Services\EletronicsService;
use Tests\TestCase;

class EletronicsUnitTest extends TestCase
{
    public EletronicsService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(EletronicsService::class);
    }

    /**
     * @testdox create - default
     */
    public function testCreateEletronics()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();

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
        $resource = $this->service->create(CreateEletronicsDto::make($resourceData), $user, 'default');
        $resourceModel = Eletronics::find($resource['id']);

        // Testing relations
        $this->assertSame($platform->id, $resourceModel->platform_id, 'platform_id');
        $this->assertSame($user->id, $resourceModel->user_id, 'user_id');
        $this->assertSame($cartegory->id, $resourceModel->category_id, 'category_id');
        $this->assertSame($user->id, $resourceModel->created_by, 'created_by');

        // Testing output transformer
        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertSame($resourceData['title'], $resource['title'], 'title');
        $this->assertSame($resourceData['body'], $resource['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $resource['is_featured'], 'is_featured');
        $this->assertDateLessOrEqualThanNow($resource['published_at'], 'published_at');
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
    public function testUpdateEletronics()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();

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
        $resource = $this->service->update($resource['id'], UpdateEletronicsDto::make($resourceData), $user, 'default');

        $this->assertGreaterThanOrEqual(1, $resource['id']);
        $this->assertSame($resourceData['title'], $resource['title'], 'title');
        $this->assertSame($resourceData['body'], $resource['body'], 'body');
        $this->assertSame($resourceData['is_featured'], $resource['is_featured'], 'is_featured');
            $this->assertDateLessOrEqualThanNow($resource['published_at'], 'published_at');
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
    public function testGetEletronics()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
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
        $resourceGet = $this->service->get($resource->id, $user);

        // Testing output transformer
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
    public function testSearchEletronics()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
        ])->create();

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
        $results = $this->service->search(SearchEletronicsDto::make([
            "filters" => [
                "id" => 1
            ]
        ]), $user);
        $this->assertEquals(1, $results['count']);
    }

    /**
     * @testdox delete - default
     */
    public function testDeleteEletronics()
    {
        /*
        |--------------------------------------------------------------------------
        | Generate Mocks
        |--------------------------------------------------------------------------
        */
        $platform = Platforms::factory()->create();
        $usersType = UsersTypes::factory()->create();
        $user = User::factory([
            'platform_id' => $platform->id,
            'type_id' => $usersType->id,
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
        $this->service->delete($resource['id'], $user);

        $this->assertFalse(!!Eletronics::query()->find($resource['id'])->enabled);
    }
}
