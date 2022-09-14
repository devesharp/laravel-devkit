@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\Patterns\Dto\AbstractDto;
@if(@$options['template'] == 'search')
use Devesharp\Patterns\Dto\Templates\SearchTemplateDto;
@endif
@if(@$options['template'] == 'delete')
use Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto;
@endif
@if(@$options['template'] == 'update')
use {{ $namespaceApp }}\Create{{ $resourceName }}Dto;
@endif

class {{@$options['template'] == 'create' ? 'Create' : ''}}{{@$options['template'] == 'delete' ? 'Delete' : ''}}{{@$options['template'] == 'update' ? 'Update' : ''}}{{@$options['template'] == 'search' ? 'Search' : ''}}{{ $resourceName }}Dto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
@if(@$options['template'] == 'search')
        $this->extendRules(SearchTemplateDto::class);

@endif
@if(@$options['template'] == 'delete')
        $this->extendRules(ActionManyTemplateDto::class);

@endif
@if(@$options['template'] == 'update')
        $this->extendRules(Create{{ $resourceName }}Dto::class);
        $this->disableRequiredValues();

@endif
        return [
@if(@$options['template'] != 'search' && @$options['template'] != 'delete' && @$options['template'] != 'update')
@foreach($fieldsDto as $field)
            '{{ $field['name'] }}' => ['{{ $field['rules'] }}', '{{ $field['description'] }}'],
@endforeach
@endif
@if(@$options['template'] == 'search')
@foreach($fieldsDtoSearch as $field)
            '{{ $field['name'] }}' => ['{{ $field['rules'] }}', '{{ $field['description'] }}'],
@endforeach
@endif
        ];
    }
}