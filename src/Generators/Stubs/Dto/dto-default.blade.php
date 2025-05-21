@include('devesharp-generators::commons.header')

class {{@$template == 'create' ? 'Create' : ''}}{{@$template == 'delete' ? 'Delete' : ''}}{{@$template == 'update' ? 'Update' : ''}}{{@$template == 'search' ? 'Search' : ''}}{{ $resourceName }}Dto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
@if(@$template == 'search')
        $this->extendRules(SearchTemplateDto::class);

@endif
@if(@$template == 'delete')
        $this->extendRules(ActionManyTemplateDto::class);

@endif
@if(@$template == 'update')
        $this->extendRules(Create{{ $resourceName }}Dto::class);
        $this->disableRequiredValues();

@endif
        return [
@if(@$template != 'search' && @$template != 'delete' && @$template != 'update')
@foreach($fieldsDto as $field)
            '{{ $field['name'] }}' => new Rule('{{ $field['rules'] }}', '{{ $field['description'] }}'),
@endforeach
@endif
@if(@$template == 'search')
@foreach($fieldsDtoSearch as $field)
            '{{ $field['name'] }}' => new Rule('{{ $field['rules'] }}', '{{ $field['description'] }}'),
@endforeach
@endif
        ];
    }
}