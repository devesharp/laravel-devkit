@include('devesharp-generators::commons.header')

class {{ $resourceName }}Policy
{
    function create($requester) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function update($requester, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function get($requester, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function search($requester) {
        // \App\Exceptions\Exception::Unauthorized();
    }

    function delete($requester, $model) {
        // \App\Exceptions\Exception::Unauthorized();
    }
}
