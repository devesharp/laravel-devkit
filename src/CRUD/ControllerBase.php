<?php

namespace Devesharp\CRUD;

class ControllerBase
{
    protected $auth;

    public function __construct()
    {
        $this->auth = function_exists('auth') ? auth() : null;
    }
}
