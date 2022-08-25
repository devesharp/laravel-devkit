<?php

namespace Devesharp\APIDocs;

abstract class RoutesDocAbstract
{
    abstract public function getRouteInfo(string $name): RoutesDocInfo;
}
