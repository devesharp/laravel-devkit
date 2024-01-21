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
@if($field['subType'] == "cpf")
        $transform['{{$field['name']}}'] = format(CPFAndCNPJFormatter::class, $model->{{$field['name']}});
@elseif($field['subType'] == "phone")
        $transform['{{$field['name']}}'] = format(PhoneFormatter::class, $model->{{$field['name']}});
@elseif($field['subType'] == "rg")
        $transform['{{$field['name']}}'] = format(RGFormatter::class, $model->{{$field['name']}});
@elseif($field['subType'] == "cep")
        $transform['{{$field['name']}}'] = format(CEPFormatter::class, $model->{{$field['name']}});
@elseif($field['type'] == "number")
        $transform['{{$field['name']}}'] = @if(!$field['nullable']) (int) @endif $model->{{$field['name']}};
@elseif($field['type'] == "float")
        $transform['{{$field['name']}}'] = (float) $model->{{$field['name']}};
@elseif($field['type'] == "bool")
        $transform['{{$field['name']}}'] = (bool) $model->{{$field['name']}};
@elseif($field['type'] == "date" || $field['type'] == "datetime" || $field['type'] == "timestamp" || $field['type'] == "time")
    $transform['{{$field['name']}}'] = format(DateTimeISOFormatter::class, $model->{{$field['name']}});
@elseif($field['type'] == "date")
        $transform['{{$field['name']}}'] = format(DateTimeISOFormatter::class, $model->{{$field['name']}});
@else
        $transform['{{$field['name']}}'] = $model->{{$field['name']}};
@endif
@endforeach

        return $transform;
    }
}
