<?php

namespace Devesharp\Generators\Common;


class GeneratorConfig
{
    public string $controllerNamespace = "";
    public string $dtoNamespace = "";
    public string $serviceNamespace = "";
    public string $factoryNamespace = "";
    public string $modelNamespace = "";
    public string $policyNamespace = "";
    public string $presenterNamespace = "";
    public string $repositoryNamespace = "";
    public string $routeDocsNamespace = "";
    public string $transformInterfaceNamespace = "";
    public string $transformerNamespace = "";
    public string $migrationNamespace = "";
    public string $testRouteNamespace = "";
    public string $testUnitNamespace = "";

    public string $controllerPath = "";
    public string $dtoPath = "";
    public string $servicePath = "";
    public string $factoryPath = "";
    public string $modelPath = "";
    public string $policyPath = "";
    public string $presenterPath = "";
    public string $repositoryPath = "";
    public string $routeDocsPath = "";
    public string $transformInterfacePath = "";
    public string $transformerPath = "";
    public string $migrationPath = "";
    public string $testRoutePath = "";
    public string $testUnitPath = "";

    function init() {
        $this->controllerNamespace = config('devesharp_generator.namespace.controller', 'App\Modules\{{ModuleName}}\Controller');
        $this->dtoNamespace = config('devesharp_generator.namespace.dto', 'App\Modules\{{ModuleName}}\Dto');
        $this->serviceNamespace = config('devesharp_generator.namespace.service', 'App\Modules\{{ModuleName}}\Service');
        $this->factoryNamespace = config('devesharp_generator.namespace.factory', 'App\Modules\{{ModuleName}}\Resources\Factory');
        $this->modelNamespace = config('devesharp_generator.namespace.model', 'App\Modules\{{ModuleName}}\Resources\Model');
        $this->policyNamespace = config('devesharp_generator.namespace.policy', 'App\Modules\{{ModuleName}}\Policy');
        $this->presenterNamespace = config('devesharp_generator.namespace.presenter', 'App\Modules\{{ModuleName}}\Resources\Presenter');
        $this->repositoryNamespace = config('devesharp_generator.namespace.repository', 'App\Modules\{{ModuleName}}\Resources\Repository');
        $this->routeDocsNamespace = config('devesharp_generator.namespace.routeDocs', 'App\Modules\{{ModuleName}}\Supports\Docs');
        $this->transformInterfaceNamespace = config('devesharp_generator.namespace.transformInterface', 'App\Modules\{{ModuleName}}\Interfaces');
        $this->transformerNamespace = config('devesharp_generator.namespace.transformer', 'App\Modules\{{ModuleName}}\Transformer');
        $this->testRouteNamespace = config('devesharp_generator.namespace.testRoute', 'Testes\Routes\{{ModuleName}}');
        $this->testUnitNamespace = config('devesharp_generator.namespace.testUnit', 'Tests\Units\{{ModuleName}}');

        $this->controllerPath = config('devesharp_generator.path.controller', app_path('Modules/{{ModuleName}}/Controller'));
        $this->dtoPath = config('devesharp_generator.path.dto', app_path('Modules/{{ModuleName}}/Dto'));
        $this->servicePath = config('devesharp_generator.path.service', app_path('Modules/{{ModuleName}}/Service'));
        $this->factoryPath = config('devesharp_generator.path.factory', app_path('Modules/{{ModuleName}}/Resources/Factory'));
        $this->modelPath = config('devesharp_generator.path.model', app_path('Modules/{{ModuleName}}/Resources/Model'));
        $this->policyPath = config('devesharp_generator.path.policy', app_path('Modules/{{ModuleName}}/Policy'));
        $this->presenterPath = config('devesharp_generator.path.presenter', app_path('Modules/{{ModuleName}}/Resources/Presenter'));
        $this->repositoryPath = config('devesharp_generator.path.repository', app_path('Modules/{{ModuleName}}/Resources/Repository'));
        $this->routeDocsPath = config('devesharp_generator.path.routeDocs', app_path('Modules/{{ModuleName}}/Supports/Docs'));
        $this->transformInterfacePath = config('devesharp_generator.path.transformInterface', app_path('Modules/{{ModuleName}}/Interfaces'));
        $this->transformerPath = config('devesharp_generator.path.transformer', app_path('Modules/{{ModuleName}}/Transformer'));
        $this->migrationPath = config('devesharp_generator.path.migration', base_path('database/migrations'));
        $this->testRoutePath = config('devesharp_generator.path.testRoute', base_path('tests/Routes/{{ModuleName}}'));
        $this->testUnitPath = config('devesharp_generator.path.testUnit', base_path('tests/Units/{{ModuleName}}'));
    }

    function getNamespace(string $type): string {
        $namespace = $this->{$type . 'Namespace'};
        return $namespace;
    }
}
