<?php

namespace Devesharp\Patterns\Controller;

class ControllerBase
{
    protected $auth;

    public function __construct()
    {
        if (function_exists('auth') && auth()->user()) {
            $this->auth = auth()->user();
        }
    }
}
