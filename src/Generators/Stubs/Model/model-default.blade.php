@include('devesharp-generators::commons.header')

/**
 * Class {{ $resourceName }}
 * @package {{ $namespaceApp }}
 *
@foreach($fieldsPropertyPHPDocs as $field)
 * @property {{$field['type']}} ${{$field['name']}}
@endforeach
@if($withPresenter)
 * @@method {{ $presenterNamespace }}\{{ $resourceName }}Presenter present()
@endif
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
@foreach($fieldsModelCasts as $field)
        '{{ $field['name'] }}' => '{{ $field['cast'] }}',
@endforeach
    ];

@if($withFactory)
    protected static function newFactory()
    {
        return {{ $resourceName }}Factory::new();
    }
@endif
@if(!empty($modelRelationsFunctions))

    {!! $modelRelationsFunctions !!}
@endif
}
