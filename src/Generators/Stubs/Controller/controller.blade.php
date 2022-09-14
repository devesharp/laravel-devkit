@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\Patterns\Controller\ControllerBase;
use {{ $dtoNamespace }}\Create{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Search{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Update{{ $resourceName }}Dto;
@if($withTransformerInterface)
use {{ $transformerInterfaceNamespace }}\{{ $resourceName }}TransformerType;
@endif

class {{ $resourceName }}Controller extends ControllerBase
{
    public function __construct(
        protected \{{ $serviceNamespace }}\{{ $resourceName }}Service $service
    ) {
        parent::__construct();
    }

    public function search()
    {
        return $this->service->search(Search{{ $resourceName }}Dto::make(request()->all()), $this->auth, @if($withTransformerInterface){{ $resourceName }}TransformerType::default @else'default'@endif);
    }

    public function get($id)
    {
        return $this->service->get($id, $this->auth);
    }

    public function update($id)
    {
        return $this->service->update($id, Update{{ $resourceName }}Dto::make(request()->all()), $this->auth, @if($withTransformerInterface){{ $resourceName }}TransformerType::default @else'default'@endif);
    }

    public function create()
    {
        return $this->service->create(Create{{ $resourceName }}Dto::make(request()->all()), $this->auth, @if($withTransformerInterface){{ $resourceName }}TransformerType::default @else'default'@endif);
    }

    public function delete($id)
    {
        return $this->service->delete($id, $this->auth);
    }
}
