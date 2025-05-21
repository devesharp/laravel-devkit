@include('devesharp-generators::commons.header')

class {{ $resourceName }}Policy
{
    function create($requester) {
        if (!$requester->can(UsersPermissions::{{$resourceNameUpperSnake}}_CREATE)) {
            \Devesharp\Exceptions\Exception::Unauthorized();
        }
    }

    function update($requester, $model) {
        if (!$requester->can(UsersPermissions::{{$resourceNameUpperSnake}}_UPDATE)) {
            \Devesharp\Exceptions\Exception::Unauthorized();
        }
    }

    function get($requester, $model) {
        if (!$requester->can(UsersPermissions::{{$resourceNameUpperSnake}}_VIEW)) {
            \Devesharp\Exceptions\Exception::Unauthorized();
        }
    }

    function search($requester) {
        if (!$requester->can(UsersPermissions::{{$resourceNameUpperSnake}}_SEARCH)) {
            \Devesharp\Exceptions\Exception::Unauthorized();
        }
    }

    function delete($requester, $model = null) {
        if (!$requester->can(UsersPermissions::{{$resourceNameUpperSnake}}_DELETE)) {
            \Devesharp\Exceptions\Exception::Unauthorized();
        }
    }
}
