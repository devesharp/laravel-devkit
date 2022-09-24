<?php

namespace Devesharp\SwaggerGenerator;

/**
 * Nome e descrição da rota
 */
abstract class RoutesDocAbstract
{
    abstract public function getRouteInfo(string $name): RoutesDocInfo;
}
