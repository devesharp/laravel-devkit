@include('devesharp-generators::commons.header')

class {{ $resourceName }}RouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/{{ $resourceURI }}
     */
    public function testRoute{{ $resourceName }}Create()
    {
        @include('devesharp-generators::Tests/commons.header-test', ['user_token' => true,'forDocs' => true])

        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_CREATE, UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/{{ $resourceURI }}')
            ->setRouteInfo('Create{{ $resourceName }}', {{ $resourceName }}RouteDocs::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceGramaticalName }}'])
            ->addBody($resourceData, Create{{ $resourceName }}Dto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['data']['{{ $field['name'] }}']);
@elseif(!$field['dto'])
        $this->assertTrue(isset($responseData['data']['{{ $field['name'] }}']), '{{ $field['name'] }}');
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at" || !empty($field['now']))
        $this->assertDateLessOrEqualThanNow($responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox [POST] /v1/{{ $resourceURI }}/:id
     */
    public function testRoute{{ $resourceName }}Update()
    {
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'update' => true, 'user_token' => true,'forDocs' => true])
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_UPDATE, UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/{{ $resourceURI }}/:id')
            ->addPath('id', $resource->id, 'ID do {{ $resourceGramaticalName }}')
            ->setRouteInfo('Update{{ $resourceName }}', {{ $resourceName }}RouteDocs::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceGramaticalName }}'])
            ->addBody($resourceData, Update{{ $resourceName }}Dto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['data']['{{ $field['name'] }}']);
@elseif(!$field['dto'])
        $this->assertTrue(isset($responseData['data']['{{ $field['name'] }}']), '{{ $field['name'] }}');
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at" || !empty($field['now']))
        $this->assertDateLessOrEqualThanNow($responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox [GET] /v1/{{ $resourceURI }}/:id
     */
    public function testRoute{{ $resourceName }}Get()
    {
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'user_token' => true,'forDocs' => true])
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withGet('/v1/{{ $resourceURI }}/:id')
            ->addPath('id', $resource->id, 'ID do {{ $resourceGramaticalName }}')
            ->setRouteInfo('Get{{ $resourceName }}', {{ $resourceName }}RouteDocs::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceGramaticalName }}'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['data']['{{ $field['name'] }}']);
@elseif(!$field['dto'])
        $this->assertTrue(isset($responseData['data']['{{ $field['name'] }}']), '{{ $field['name'] }}');
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at" || !empty($field['now']))
        $this->assertDateLessOrEqualThanNow($responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resource->{{ $field['name'] }}, $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resource->{{ $field['name'] }}, $responseData['data']['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox [POST] /v1/{{ $resourceURI }}/search
     */
    public function testRoute{{ $resourceName }}Search()
    {
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'search' => true, 'user_token' => true,'forDocs' => true])

        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_SEARCH]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withPost('/v1/{{ $resourceURI }}/search')
            ->setRouteInfo('Search{{ $resourceName }}', {{ $resourceName }}RouteDocs::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceGramaticalName }}'])
            ->addBody([
                'filters' => [
                    'id' => 1
                ]
            ], Search{{ $resourceName }}Dto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertEquals(1, $responseData['data']['count']);
        $this->assertEquals(1, count($responseData['data']['results']));
    }

    /**
     * @testdox [DELETE] /v1/{{ $resourceURI }}/:id
     */
    public function testRoute{{ $resourceName }}Delete()
    {
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'user_token' => true,'forDocs' => true])
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_DELETE]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $response = $this->withDelete('/v1/{{ $resourceURI }}/:id')
            ->addPath('id', $resource->id, 'Id do {{ $resourceGramaticalName }}')
            ->setRouteInfo('Delete{{ $resourceName }}', {{ $resourceName }}RouteDocs::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceGramaticalName }}'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertTrue(!!$responseData['data']);
    }
}
