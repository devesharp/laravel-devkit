@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use {{ $routeDocsNamespace }}\{{ $resourceName }}RouteDoc;
use {{ $dtoNamespace }}\Create{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Search{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Update{{ $resourceName }}Dto;
use {{ $modelNamespace }}\{{ $resourceName }};
use {{ $userModelNamespace }}\Users;
use {{ $serviceNamespace }}\{{ $resourceName }}Service;
{!!  $useNamespace !!}
use Tests\TestCase;

class {{ $resourceName }}UnitTest extends TestCase
{
    public {{ $resourceName }}Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app({{ $resourceName }}Service::class);
    }

    /**
     * @testdox create - default
     */
    public function testCreate{{ $resourceName }}()
    {
{!!  $headerFnTest !!}
        $resourceData = {{ $resourceName }}::factory()->raw();

        $resource = $this->service->create(Create{{ $resourceName }}Dto::make($resourceData), ${{ $userVariable }}, 'default');

        $this->assertGreaterThanOrEqual(1, $resource['id']);
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $resource['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at")
        $this->assertDateLessOrEqualThanNow($resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox update - default
     */
    public function testUpdate{{ $resourceName }}()
    {
{!!  $headerFnTest !!}
        $resource = {{ $resourceName }}::factory()->create();
        $resourceData = {{ $resourceName }}::factory()->raw();

        $resource = $this->service->update($resource['id'], Update{{ $resourceName }}Dto::make($resourceData), ${{ $userVariable }}, 'default');

@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $resource['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at")
        $this->assertDateLessOrEqualThanNow($resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox get - default
     */
    public function testGet{{ $resourceName }}()
    {
{!!  $headerFnTest !!}
        $resourceOther = {{ $resourceName }}::factory()->create();
        $resource = {{ $resourceName }}::factory()->create();

        $resourceGet = $this->service->get($resource->id, ${{ $userVariable }});

@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual($resource->id, $resourceGet['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at")
        $this->assertDateLessOrEqualThanNow($resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date")
        $this->assertDateEqual($resource->{{ $field['name'] }}, $resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
@else
        $this->assertSame($resource->{{ $field['name'] }}, $resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
@endif
@endforeach
    }

    /**
     * @testdox search - default
     */
    public function testSearch{{ $resourceName }}()
    {
{!!  $headerFnTest !!}
        {{ $resourceName }}::factory()->count(5)->create();

        $results = $this->service->search(Search{{ $resourceName }}Dto::make([
            "filters" => [
                "id" => 1
            ]
        ]), ${{ $userVariable }});
        $this->assertEquals(1, $results['count']);
    }

    /**
     * @testdox delete - default
     */
    public function testDelete{{ $resourceName }}()
    {
{!!  $headerFnTest !!}
        $resourceData = {{ $resourceName }}::factory()->raw();

        $resource = $this->service->create(Create{{ $resourceName }}Dto::make($resourceData), ${{ $userVariable }});

        $this->service->delete($resource['id'], ${{ $userVariable }});

        $this->assertFalse(!!{{ $resourceName }}::query()->find($resource['id'])->enabled);
    }
}
