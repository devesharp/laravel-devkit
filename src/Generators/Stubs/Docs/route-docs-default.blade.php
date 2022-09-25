@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\APIDocs\RoutesDocAbstract;
use Devesharp\APIDocs\RoutesDocInfo;

class {{ $resourceName }}RouteDoc extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "Get{{ $resourceName }}" => new RoutesDocInfo("Resgatar {{ $resourceNameForDocs }}", "Resgatar {{ $resourceNameForDocs }}"),
            "Create{{ $resourceName }}" => new RoutesDocInfo("Criar {{ $resourceNameForDocs }}", "Criar {{ $resourceNameForDocs }}"),
            "Update{{ $resourceName }}" => new RoutesDocInfo("Atualizar {{ $resourceNameForDocs }}", "Atualizar {{ $resourceNameForDocs }}"),
            "Search{{ $resourceName }}" => new RoutesDocInfo("Buscar {{ $resourceNameForDocs }}", "Buscar {{ $resourceNameForDocs }}"),
            "Delete{{ $resourceName }}" => new RoutesDocInfo("Deletar {{ $resourceNameForDocs }}", "Deletar {{ $resourceNameForDocs }}"),
            default => new RoutesDocInfo("", ""),
        };
    }
}