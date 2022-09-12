<?php

namespace App\Modules\ModuleExample\Supports\Docs;

use Devesharp\APIDocs\RoutesDocAbstract;
use Devesharp\APIDocs\RoutesDocInfo;

class ResourceExampleRouteDoc extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "CreateResourceExample" => new RoutesDocInfo("Criar ResourceExample", "Criar ResourceExample"),
            "UpdateResourceExample" => new RoutesDocInfo("Atualizar ResourceExample", "Atualizar ResourceExample"),
            "SearchResourceExample" => new RoutesDocInfo("Buscar ResourceExample", "Buscar ResourceExample"),
            "DeleteResourceExample" => new RoutesDocInfo("Deletar ResourceExample", "Deletar ResourceExample"),
            default => new RoutesDocInfo("", ""),
        };
    }
}