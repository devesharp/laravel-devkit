<?php

namespace App\Modules\Products\Supports\Docs;

use Devesharp\APIDocs\RoutesDocAbstract;
use Devesharp\APIDocs\RoutesDocInfo;

class EletronicsRouteDoc extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "GetEletronics" => new RoutesDocInfo("Resgatar Eletronics", "Resgatar Eletronics"),
            "CreateEletronics" => new RoutesDocInfo("Criar Eletronics", "Criar Eletronics"),
            "UpdateEletronics" => new RoutesDocInfo("Atualizar Eletronics", "Atualizar Eletronics"),
            "SearchEletronics" => new RoutesDocInfo("Buscar Eletronics", "Buscar Eletronics"),
            "DeleteEletronics" => new RoutesDocInfo("Deletar Eletronics", "Deletar Eletronics"),
            default => new RoutesDocInfo("", ""),
        };
    }
}