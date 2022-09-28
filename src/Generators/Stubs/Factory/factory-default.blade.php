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
    * @return array
    */
    public function bodyForRequest(): array
    {
        return [
@foreach($fieldsFaker as $field)
@if($field['request'])
            '{{ $field['name'] }}' => {!! $field['faker_function'] !!},
@endif
@endforeach
        ];
    }
}
