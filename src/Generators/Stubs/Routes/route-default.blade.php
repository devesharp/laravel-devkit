Route::controller(\{{$controllerNamespace}}\{{ $resourceName }}Controller::class)->group(function () {
    Route::post('{{ $resourceURI }}/search', 'search');
    Route::get('{{ $resourceURI }}/{id}', 'get');
    Route::post('{{ $resourceURI }}/{id}', 'update');
    Route::post('{{ $resourceURI }}', 'create');
    Route::delete('{{ $resourceURI }}/{id}', 'delete');
});