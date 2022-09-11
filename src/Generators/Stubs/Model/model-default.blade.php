@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

@if($withPresenter)
use {{ $presenterNamespace }}\{{ $resourceName }}Presenter;
use Devesharp\Patterns\Presenter\PresentableTrait;
@endif
@if($withFactory)
use {{ $factoryNamespace }}\{{ $resourceName }}Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
@endif
use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class {{ $resourceName }}
 * @package {{ $namespaceApp }}\
 *

 * @@method static {{ $namespaceApp }}\{{ $resourceName }} find($value)
 */
class {{ $resourceName }} extends Model
{
    use ModelGetTable;
@if($withPresenter)
    use PresentableTrait;
@endif
@if($withFactory)
    use HasFactory;
@endif

    protected $table = '{{ $tableName }}';

@if($withPresenter)
    protected string $presenter = {{ $resourceName }}Presenter::class;

@endif
    protected $guarded = [];

    protected $casts = [
@foreach($fieldsCasts as $field)
@if($field['cast'] !== 'date')
        '{{ $field['name'] }}' => '{{ $field['cast'] }}',
@endif
@endforeach
    ];

@if(\Devesharp\Support\Collection::make($fieldsCasts)->some(fn($f) => $f['cast'] == 'date'))
    protected $dates = [
@foreach($fieldsCasts as $field)
@if($field['cast'] === 'date')
        '{{ $field['name'] }}',
@endif
@endforeach
    ];
@endif
@if($withFactory)
    protected static function newFactory()
    {
        return {{ $resourceName }}Factory::new();
    }
@endif
}
