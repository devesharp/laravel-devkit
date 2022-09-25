
Route::controller(\App\Modules\Example\Resources\Controllers\ExampleController::class)->group(function () {
    Route::post('example/search', 'search');
    Route::get('example/{id}', 'get');
    Route::post('example/{id}', 'update');
    Route::post('example', 'create');
    Route::delete('example/{id}', 'delete');
});