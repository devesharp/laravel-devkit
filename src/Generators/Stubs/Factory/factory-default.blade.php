@include('devesharp-generators::commons.header')

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

    /**
    * Define the model's default state.
    *
    */
    public function bodyForRequest()
    {
        return $this->state(function (array $attributes) {
            return [
@foreach($fieldsFaker as $field)
@if($field['request'])
                '{{ $field['name'] }}' => {!! $field['faker_function'] !!},
@endif
@endforeach
            ];
        });
    }

    /**
    * Body para gerar documentação mais acertiva com os valores gerados
    *
    */
    public function bodyForDocs()
    {
        return [
        @foreach($fieldsFakerForDocs as $field)
        '{{ $field['name'] }}' => {!! $field['value'] !!},
        @endforeach
        ];
    }
}
