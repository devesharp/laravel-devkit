@include('devesharp-generators::commons.header')

class {{ $resourceName }}Transformer extends Transformer
{
    public $model = {{ $resourceName }}::class;

    protected array $loads = [];

    /**
     * @param $model
     * @param string $context
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function transformDefault(
        $model,
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }

        $transform = [];
@foreach($fieldsTransformer as $field)
@if($field['type'] == "float")
        $transform['{{$field['name']}}'] = (float) $model->{{$field['name']}};
@elseif($field['type'] == "bool")
        $transform['{{$field['name']}}'] = (bool) $model->{{$field['name']}};
@else
        $transform['{{$field['name']}}'] = $model->{{$field['name']}};
@endif
@endforeach

        return $transform;
    }
}
