Route::controller(\{{$controllerNamespace}}\{{ $resourceName }}Controller::class)->group(function () {
    Route::post('{{ $routeName }}/search', 'search');
    Route::get('{{ $routeName }}/{id}', 'get');
    Route::post('{{ $routeName }}/{id}', 'update');
    Route::post('{{ $routeName }}', 'create');
    Route::delete('{{ $routeName }}/{id}', 'delete');
});