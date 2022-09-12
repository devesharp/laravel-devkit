@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

class {{ $resourceName }}Policy
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
