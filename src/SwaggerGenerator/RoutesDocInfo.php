<?php

namespace Devesharp\SwaggerGenerator;

class RoutesDocInfo
{
    public string $name;
    public string $description;

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}
