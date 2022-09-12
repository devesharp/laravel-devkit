@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\Patterns\Transformer\Transformer;
use {{ $modelNamespace }}\{{ $resourceName }};

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
        $transform['{{$field['name']}}'] = $model->{{$field['name']}};
@endforeach

        return $transform;
    }
}
