@include('devesharp-generators::commons.header')

class {{ $resourceName }}RouteDocs extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "Get{{ $resourceName }}" => new RoutesDocInfo("Resgatar {{ $resourceGramaticalName }}", "Resgatar {{ $resourceGramaticalName }}"),
            "Create{{ $resourceName }}" => new RoutesDocInfo("Criar {{ $resourceGramaticalName }}", "Criar {{ $resourceGramaticalName }}"),
            "Update{{ $resourceName }}" => new RoutesDocInfo("Atualizar {{ $resourceGramaticalName }}", "Atualizar {{ $resourceGramaticalName }}"),
            "Search{{ $resourceName }}" => new RoutesDocInfo("Buscar {{ $resourceGramaticalName }}", "Buscar {{ $resourceGramaticalName }}"),
            "Delete{{ $resourceName }}" => new RoutesDocInfo("Deletar {{ $resourceGramaticalName }}", "Deletar {{ $resourceGramaticalName }}"),
            default => new RoutesDocInfo("", ""),
        };
    }
}