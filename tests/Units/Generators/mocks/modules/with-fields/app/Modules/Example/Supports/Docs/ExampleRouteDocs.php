<?php

namespace App\Modules\Example\Supports\Docs;

use Devesharp\APIDocs\RoutesDocAbstract;
use Devesharp\APIDocs\RoutesDocInfo;

class ExampleRouteDoc extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "GetExample" => new RoutesDocInfo("Resgatar Example", "Resgatar Example"),
            "CreateExample" => new RoutesDocInfo("Criar Example", "Criar Example"),
            "UpdateExample" => new RoutesDocInfo("Atualizar Example", "Atualizar Example"),
            "SearchExample" => new RoutesDocInfo("Buscar Example", "Buscar Example"),
            "DeleteExample" => new RoutesDocInfo("Deletar Example", "Deletar Example"),
            default => new RoutesDocInfo("", ""),
        };
    }
}