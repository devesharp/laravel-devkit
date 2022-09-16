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
    public string $transformerInterfaceNamespace = "";
    public string $transformerNamespace = "";
    public string $migrationNamespace = "";
    public string $testRouteNamespace = "";
    public string $testUnitNamespace = "";
    public string $formatterNamespace = "";

    public string $controllerPath = "";
    public string $dtoPath = "";
    public string $servicePath = "";
    public string $factoryPath = "";
    public string $modelPath = "";
    public string $policyPath = "";
    public string $presenterPath = "";
    public string $repositoryPath = "";
    public string $routeDocsPath = "";
    public string $transformerInterfacePath = "";
    public string $transformerPath = "";
    public string $migrationPath = "";
    public string $testRoutePath = "";
    public string $testUnitPath = "";
    public string $apiRoutesPath = "";

    public string $controllerSuffix = "";
    public string $dtoSuffix = "";
    public string $serviceSuffix = "";
    public string $factorySuffix = "";
    public string $modelSuffix = "";
    public string $policySuffix = "";
    public string $presenterSuffix = "";
    public string $repositorySuffix = "";
    public string $routeDocsSuffix = "";
    public string $transformerInterfaceSuffix = "";
    public string $transformerSuffix = "";
    public string $migrationSuffix = "";
    public string $testRouteSuffix = "";
    public string $testUnitSuffix = "";

    public string $controllerPrefix = "";
    public string $dtoPrefix = "";
    public string $servicePrefix = "";
    public string $factoryPrefix = "";
    public string $modelPrefix = "";
    public string $policyPrefix = "";
    public string $presenterPrefix = "";
    public string $repositoryPrefix = "";
    public string $routeDocsPrefix = "";
    public string $transformerInterfacePrefix = "";
    public string $transformerPrefix = "";
    public string $migrationPrefix = "";
    public string $testRoutePrefix = "";
    public string $testUnitPrefix = "";

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
        $this->transformerInterfaceNamespace = config('devesharp_generator.namespace.transformerInterface', 'App\Modules\{{ModuleName}}\Interfaces');
        $this->transformerNamespace = config('devesharp_generator.namespace.transformer', 'App\Modules\{{ModuleName}}\Transformer');
        $this->testRouteNamespace = config('devesharp_generator.namespace.testRoute', 'Testes\Routes\{{ModuleName}}');
        $this->testUnitNamespace = config('devesharp_generator.namespace.testUnit', 'Tests\Units\{{ModuleName}}');
        $this->formatterNamespace = config('devesharp_generator.namespace.formatter', 'App\Supports\Formatters');

        $this->controllerPath = config('devesharp_generator.path.controller', app_path('Modules/{{ModuleName}}/Controller'));
        $this->dtoPath = config('devesharp_generator.path.dto', app_path('Modules/{{ModuleName}}/Dto'));
        $this->servicePath = config('devesharp_generator.path.service', app_path('Modules/{{ModuleName}}/Service'));
        $this->factoryPath = config('devesharp_generator.path.factory', app_path('Modules/{{ModuleName}}/Resources/Factory'));
        $this->modelPath = config('devesharp_generator.path.model', app_path('Modules/{{ModuleName}}/Resources/Model'));
        $this->policyPath = config('devesharp_generator.path.policy', app_path('Modules/{{ModuleName}}/Policy'));
        $this->presenterPath = config('devesharp_generator.path.presenter', app_path('Modules/{{ModuleName}}/Resources/Presenter'));
        $this->repositoryPath = config('devesharp_generator.path.repository', app_path('Modules/{{ModuleName}}/Resources/Repository'));
        $this->routeDocsPath = config('devesharp_generator.path.routeDocs', app_path('Modules/{{ModuleName}}/Supports/Docs'));
        $this->transformerInterfacePath = config('devesharp_generator.path.transformerInterface', app_path('Modules/{{ModuleName}}/Interfaces'));
        $this->transformerPath = config('devesharp_generator.path.transformer', app_path('Modules/{{ModuleName}}/Transformer'));
        $this->migrationPath = config('devesharp_generator.path.migration', base_path('database/migrations'));
        $this->testRoutePath = config('devesharp_generator.path.testRoute', base_path('tests/Routes/{{ModuleName}}'));
        $this->testUnitPath = config('devesharp_generator.path.testUnit', base_path('tests/Units/{{ModuleName}}'));
        $this->apiRoutesPath = config('devesharp_generator.path.api_routes', base_path('api/api.php'));

        $this->controllerPrefix = config('devesharp_generator.prefix.controller', '');
        $this->dtoPrefix = config('devesharp_generator.prefix.dto', '');
        $this->servicePrefix = config('devesharp_generator.prefix.service', '');
        $this->factoryPrefix = config('devesharp_generator.prefix.factory', '');
        $this->modelPrefix = config('devesharp_generator.prefix.model', '');
        $this->policyPrefix = config('devesharp_generator.prefix.policy', '');
        $this->presenterPrefix = config('devesharp_generator.prefix.presenter', '');
        $this->repositoryPrefix = config('devesharp_generator.prefix.repository', '');
        $this->routeDocsPrefix = config('devesharp_generator.prefix.routeDocs', '');
        $this->transformerInterfacePrefix = config('devesharp_generator.prefix.transformerInterface', '');
        $this->transformerPrefix = config('devesharp_generator.prefix.transformer', '');
        $this->migrationPrefix = config('devesharp_generator.prefix.migration', '');
        $this->testRoutePrefix = config('devesharp_generator.prefix.testRoute', '');
        $this->testUnitPrefix = config('devesharp_generator.prefix.testUnit', '');

        $this->controllerSuffix = config('devesharp_generator.prefix.controller', 'Controller');
        $this->dtoSuffix = config('devesharp_generator.prefix.dto', 'Dto');
        $this->serviceSuffix = config('devesharp_generator.prefix.service', 'Service');
        $this->factorySuffix = config('devesharp_generator.prefix.factory', 'Factory');
        $this->modelSuffix = config('devesharp_generator.prefix.model', '');
        $this->policySuffix = config('devesharp_generator.prefix.policy', 'Policy');
        $this->presenterSuffix = config('devesharp_generator.prefix.presenter', 'Presenter');
        $this->repositorySuffix = config('devesharp_generator.prefix.repository', 'Repository');
        $this->routeDocsSuffix = config('devesharp_generator.prefix.routeDocs', 'RouteDocs');
        $this->transformerInterfaceSuffix = config('devesharp_generator.prefix.transformerInterface', 'TransformerType');
        $this->transformerSuffix = config('devesharp_generator.prefix.transformer', 'transformer');
        $this->migrationSuffix = config('devesharp_generator.prefix.migration', '');
        $this->testRouteSuffix = config('devesharp_generator.prefix.testRoute', 'RouteTest');
        $this->testUnitSuffix = config('devesharp_generator.prefix.testUnit', 'UnitTest');
    }

    function getNamespace(string $type): string {
        $namespace = $this->{$type . 'Namespace'};
        return $namespace;
    }

    function getPath(string $type): string {
        $namespace = $this->{$type . 'Path'};
        return $namespace;
    }

    function getPrefix(string $type): string {
        $namespace = $this->{$type . 'Prefix'};
        return $namespace;
    }

    function getSuffix(string $type): string {
        $namespace = $this->{$type . 'Suffix'};
        return $namespace;
    }
}
