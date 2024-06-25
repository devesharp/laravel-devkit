@include('devesharp-generators::commons.header')

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
        @include('devesharp-generators::Tests/commons.header-test')
@if($withPolicy)
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_CREATE, UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);
@endif

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $resource = $this->service->create(Create{{ $resourceName }}Dto::make($resourceData), ${{ $userVariable }}, 'default');
        $resourceModel = {{ $resourceName }}::find($resource['id']);

@if(!empty($fieldsUsedOnResource))
        // Testing relations
@foreach($fieldsUsedOnResource as $fieldRelationTest)@if(!$fieldRelationTest['valueOnUser'])
        $this->assertSame(${{$fieldRelationTest['variable']}}->{{$fieldRelationTest['key']}}, $resourceModel->{{ $fieldRelationTest['localKey']  }}, '{{ $fieldRelationTest['localKey'] }}');
@else
        $this->assertSame($user->{{$fieldRelationTest['valueOnUser']}}, $resourceModel->{{ $fieldRelationTest['localKey']  }}, '{{ $fieldRelationTest['localKey'] }}');
@endif
@endforeach

@endif
        // Testing output transformer
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $resource['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at" || !empty($field['now']))
{{--
    Se for create_at, updated_at ou valueOnCreate.staticValue, quer dizer que o horário
    sempre será o atual, assim o teste precisa ser diferente dos demais
--}}        $this->assertDateLessOrEqualThanNow($resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif(!$field['dto'])
        $this->assertTrue(isset($resource['{{ $field['name'] }}']), '{{ $field['name'] }}');
@elseif($field['type'] == "date" && empty($field['format']))
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date" && !empty($field['format']))
        $this->assertSame($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
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
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'update' => true])
@if($withPolicy)
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_UPDATE, UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);
@endif

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $resource = $this->service->update($resource['id'], Update{{ $resourceName }}Dto::make($resourceData), ${{ $userVariable }}, 'default');

@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual(1, $resource['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at" || !empty($field['now']))
    {{--
        Se for create_at, updated_at ou valueOnCreate.staticValue, quer dizer que o horário
        sempre será o atual, assim o teste precisa ser diferente dos demais
    --}}        $this->assertDateLessOrEqualThanNow($resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif(!$field['dto'])
        $this->assertTrue(isset($resource['{{ $field['name'] }}']), '{{ $field['name'] }}');
@elseif($field['type'] == "date" && empty($field['format']))
        $this->assertDateEqual($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date" && !empty($field['format']))
        $this->assertEquals($resourceData['{{ $field['name'] }}'], $resource['{{ $field['name'] }}'], '{{ $field['name'] }}');
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
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true])
@if($withPolicy)
        // Permissions
        $user->allow([ UsersPermissions::{{$resourceNameUpperSnake}}_VIEW]);
@endif

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $resourceGet = $this->service->get($resource->id, ${{ $userVariable }});

        // Testing output transformer
@foreach($fieldsTransformer as $field)
@if($field['name'] == "id")
        $this->assertGreaterThanOrEqual($resource->id, $resourceGet['id']);
@elseif($field['name'] == "created_at" || $field['name'] == "updated_at")
        $this->assertDateLessOrEqualThanNow($resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date" && empty($field['format']))
        $this->assertDateEqual($resource->{{ $field['name'] }}, $resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
@elseif($field['type'] == "date" && !empty($field['format']))
        $this->assertEquals($resource->{{ $field['name'] }}->format('{{ $field['format'] }}'), $resourceGet['{{ $field['name'] }}'], '{{ $field['name'] }}');
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
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true, 'search' => true])
@if($withPolicy)
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_SEARCH]);
@endif

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
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
        @include('devesharp-generators::Tests/commons.header-test', ['create' => true])
@if($withPolicy)
        // Permissions
        $user->allow([UsersPermissions::{{$resourceNameUpperSnake}}_DELETE]);
@endif

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        $this->service->delete($resource['id'], ${{ $userVariable }});

@if($disableEnabledColumn)
        $this->assertEmpty(!!{{ $resourceName }}::query()->find($resource['id']));
@else
        $this->assertFalse(!!{{ $resourceName }}::query()->find($resource['id'])->enabled);
@endif
    }
}
