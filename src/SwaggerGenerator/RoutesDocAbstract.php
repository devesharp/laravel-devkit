<?php

namespace Devesharp\SwaggerGenerator;

abstract class RoutesDocAbstract
{
    abstract public function getRouteInfo(string $name): RoutesDocInfo;
}
