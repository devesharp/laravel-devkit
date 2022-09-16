<?php

namespace App\Modules\ModuleExample\Policies;

class ResourceExamplePolicy
{
    function create($request) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function update($request, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function get($request, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function search($request) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function delete($request, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }
}
