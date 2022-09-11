@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use {{ $modelNamespace }}\{{ $resourceName }};
use Devesharp\Support\Factory;

class {{ $resourceName }}Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = {{ $resourceName }}::class;

    protected $onlyRaw = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
@foreach($fieldsFaker as $field)
            '{{ $field['name'] }}' => {!! $field['faker_function'] !!},
@endforeach
        ];
    }
}
