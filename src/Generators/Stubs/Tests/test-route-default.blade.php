@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use {{ $routeDocsNamespace }}{{ $resourceName }}RouteDoc;
use {{ $dtoNamespace }}\Create{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Search{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Update{{ $resourceName }}Dto;
use {{ $modelNamespace }}\{{ $resourceName }};
use {{ $modelNamespace }}\Users;
{!!  $useNamespace !!}
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class {{ $resourceName }}RouteTest extends TestCase
{
    /**
     * @testdox [POST] /v1/{{ $routeName }}
     */
    public function testRoute{{ $resourceName }}Create()
    {
{!!  $headerFnTest !!}
        ${{ $userVariable }}->access_token = JWTAuth::fromUser(${{ $userVariable }});
        $resourceData = {{ $resourceName }}::factory()->raw();

        $response = $this->withPost('/v1/{{ $routeName }}')
            ->setRouteInfo('Create{{ $resourceName }}', {{ $resourceName }}RouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceNameForDocs }}'])
            ->addBody($resourceData, Create{{ $resourceName }}Dto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['{{ $field['name'] }}']);
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $responseData['{{ $field['name'] }}']);
@endif
@endforeach
    }

    /**
     * @testdox [POST] /v1/{{ $routeName }}/:id
     */
    public function testRoute{{ $resourceName }}Update()
    {
{!!  $headerFnTest !!}
        ${{ $userVariable }}->access_token = JWTAuth::fromUser(${{ $userVariable }});
        $resourceData = {{ $resourceName }}::factory()->raw();
        $resource = {{ $resourceName }}::factory()->create();

        $response = $this->withPost('/v1/{{ $routeName }}/:id')
            ->addPath('id', $resource->id, 'ID do {{ $resourceNameForDocs }}')
            ->setRouteInfo('Update{{ $resourceName }}', {{ $resourceName }}RouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceNameForDocs }}'])
            ->addBody($resourceData, Update{{ $resourceName }}Dto::class)
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['{{ $field['name'] }}']);
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $responseData['{{ $field['name'] }}']);
@endif
@endforeach
    }

    /**
     * @testdox [GET] /v1/{{ $routeName }}/:id
     */
    public function testRoute{{ $resourceName }}Get()
    {
{!!  $headerFnTest !!}
        ${{ $userVariable }}->access_token = JWTAuth::fromUser(${{ $userVariable }});
        $resource = {{ $resourceName }}::factory()->create();

        $response = $this->withGet('/v1/{{ $routeName }}/:id')
            ->addPath('id', $resource->id, 'ID do {{ $resourceNameForDocs }}')
            ->setRouteInfo('Get{{ $resourceName }}', {{ $resourceName }}RouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceNameForDocs }}'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $responseData['{{ $field['name'] }}']);
@else
        $this->assertSame($resource->{{ $field['name'] }}, $responseData['{{ $field['name'] }}']);
@endif
@endforeach
    }

    /**
     * @testdox [POST] /v1/{{ $routeName }}/search
     */
    public function testRoute{{ $resourceName }}Search()
    {
{!!  $headerFnTest !!}
        ${{ $userVariable }}->access_token = JWTAuth::fromUser(${{ $userVariable }});
        {{ $resourceName }}::factory()->count(3)->create();

        $response = $this->withPost('/v1/{{ $routeName }}/search')
            ->setRouteInfo('Search{{ $resourceName }}', {{ $resourceName }}RouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceNameForDocs }}'])
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
     * @testdox [DELETE] /v1/{{ $routeName }}/:id
     */
    public function testRoute{{ $resourceName }}Delete()
    {
{!!  $headerFnTest !!}
        ${{ $userVariable }}->access_token = JWTAuth::fromUser(${{ $userVariable }});

        $resource = {{ $resourceName }}::factory()->create();

        $response = $this->withDelete('/v1/{{ $routeName }}/:id')
            ->addPath('id', $resource->id, 'Id do {{ $resourceNameForDocs }}')
            ->setRouteInfo('Delete{{ $resourceName }}', {{ $resourceName }}RouteDoc::class)
            ->addHeader('Authorization', 'Bearer ' . ${{ $userVariable }}->access_token, 'Authorization')
            ->addGroups(['{{ $resourceNameForDocs }}'])
            ->run();

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertTrue($responseData['success']);
        $this->assertTrue(!!$responseData['data']);
    }
}
