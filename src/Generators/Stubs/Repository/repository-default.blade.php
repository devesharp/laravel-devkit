@include('devesharp-generators::commons.header')

/**
 * Class {{ $resourceName }}Repository
 *
 * @@method public                                      Builder getModel()
 * @@method {{ $modelNamespace }}\{{ $resourceName }} findById($id, $enabled = true)
 * @@method {{ $modelNamespace }}\{{ $resourceName }} findIdOrFail($id, $enabled = true)
 */
class {{ $resourceName }}Repository extends RepositoryMysql
{
    /**
     * @@var string
     */
    protected $model = \{{ $modelNamespace }}\{{ $resourceName }}::class;

@if($disableEnabledColumn)
    protected bool $disableEnabledColumn = true;
@endif

}
