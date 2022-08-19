<?php

namespace Devesharp\Patterns\Controller;

class ControllerBase
{
    protected $auth;

    public function __construct()
    {
        $this->auth = function_exists('auth') ? auth() : null;
    }
}
