<?php

namespace Devesharp\CRUD;

use Devesharp\CRUD\Repository\RepositoryInterface;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ControllerBase
{
    protected $auth;

    public function __construct()
    {
        $this->auth = function_exists('auth') ? auth() : null;
    }
}
