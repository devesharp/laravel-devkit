<?php

namespace App\Modules\Products\Supports\Docs;

use Devesharp\APIDocs\RoutesDocAbstract;
use Devesharp\APIDocs\RoutesDocInfo;

class EletronicsRouteDoc extends RoutesDocAbstract
{
    public function getRouteInfo(string $name): RoutesDocInfo {
        return match ($name) {
            "GetEletronics" => new RoutesDocInfo("Resgatar Eletrônicos", "Resgatar Eletrônicos"),
            "CreateEletronics" => new RoutesDocInfo("Criar Eletrônicos", "Criar Eletrônicos"),
            "UpdateEletronics" => new RoutesDocInfo("Atualizar Eletrônicos", "Atualizar Eletrônicos"),
            "SearchEletronics" => new RoutesDocInfo("Buscar Eletrônicos", "Buscar Eletrônicos"),
            "DeleteEletronics" => new RoutesDocInfo("Deletar Eletrônicos", "Deletar Eletrônicos"),
            default => new RoutesDocInfo("", ""),
        };
    }
}