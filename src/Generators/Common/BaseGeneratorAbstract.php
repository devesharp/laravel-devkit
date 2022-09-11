<?php

namespace Devesharp\Generators\Common;

//    ds:generator $typeName $module $name
abstract class BaseGeneratorAbstract
{
    public array $options = [];

    public string $moduleName = '';
    public string $resourceType = '';
    public string $resourceName = '';
    public bool $withCreate = false;
    public bool $withUpdate = false;
    public bool $withDelete = false;
    public bool $withSearch = false;
    public string $routeName = '';
    public string $NamePtBr = '';

    // Modules created by the generator
    public bool $withController = false;
    public bool $withDto = false;
    public bool $withService = false;
    public bool $withFactory = false;
    public bool $withModel = false;
    public bool $withPolicy = false;
    public bool $withPresenter = false;
    public bool $withRepository = false;
    public bool $withRouteDocs = false;
    public bool $withTransformerInterface = false;
    public bool $withTransformer = false;
    public bool $withTestRoute = false;
    public bool $withTestUnit = false;

    //
    public array $fieldsDto = [];
    public array $fieldsFaker = [];

    public function __construct(protected GeneratorConfig $config)
    {
        $config->init();
    }

    public function setData(array $data, array $options = []): self
    {
        $this->moduleName = $data['module'];
        $this->resourceName = $data['name'] ?? $data['module'];
        $this->withCreate = $data['withCreate'] ?? true;
        $this->withUpdate = $data['withUpdate'] ?? true;
        $this->withDelete = $data['withDelete'] ?? true;
        $this->withSearch = $data['withSearch'] ?? true;
        $this->routeName = $data['routeName'] ?? $this->resourceName;
        $this->NamePtBr = $data['NamePtBr'] ?? $this->resourceName;
        //
        $this->withController = $data['withController'] ?? false;
        $this->withDto = $data['withDto'] ?? false;
        $this->withService = $data['withService'] ?? false;
        $this->withFactory = $data['withFactory'] ?? false;
        $this->withModel = $data['withModel'] ?? false;
        $this->withPolicy = $data['withPolicy'] ?? false;
        $this->withPresenter = $data['withPresenter'] ?? false;
        $this->withRepository = $data['withRepository'] ?? false;
        $this->withRouteDocs = $data['withRouteDocs'] ?? false;
        $this->withTransformerInterface = $data['withTransformerInterface'] ?? false;
        $this->withTransformer = $data['withTransformer'] ?? false;
        $this->withTestRoute = $data['withTestRoute'] ?? false;
        $this->withTestUnit = $data['withTestUnit'] ?? false;

        $this->fieldsDto = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForDto() : [];
        $this->fieldsFaker = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForFaker() : [];

        $this->options = $options;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->replaceNameSpace($this->config->getNamespace($this->resourceType));
    }

    function replaceNameSpace(string $namespace): string
    {
        $namespace = str_replace('{{ModuleName}}', $this->moduleName, $namespace);
        $namespace = str_replace('{{resource}}', $this->resourceName, $namespace);

        return $namespace;
    }

    function getRootData() {
        return [
            'namespaceApp' => $this->getNamespace(),
            'moduleName' => $this->moduleName,
            'resourceName' => $this->resourceName,
            //
            'withController' => $this->withController,
            'withDto' => $this->withDto,
            'withService' => $this->withService,
            'withFactory' => $this->withFactory,
            'withModel' => $this->withModel,
            'withPolicy' => $this->withPolicy,
            'withPresenter' => $this->withPresenter,
            'withRepository' => $this->withRepository,
            'withRouteDocs' => $this->withRouteDocs,
            'withTransformerInterface' => $this->withTransformerInterface,
            'withTransformer' => $this->withTransformer,
            'withTestRoute' => $this->withTestRoute,
            'withTestUnit' => $this->withTestUnit,
            //
            'controllerNamespace' => $this->replaceNameSpace($this->config->getNamespace('controller')),
            'dtoNamespace' => $this->replaceNameSpace($this->config->getNamespace('dto')),
            'serviceNamespace' => $this->replaceNameSpace($this->config->getNamespace('service')),
            'factoryNamespace' => $this->replaceNameSpace($this->config->getNamespace('factory')),
            'modelNamespace' => $this->replaceNameSpace($this->config->getNamespace('model')),
            'policyNamespace' => $this->replaceNameSpace($this->config->getNamespace('policy')),
            'presenterNamespace' => $this->replaceNameSpace($this->config->getNamespace('presenter')),
            'repositoryNamespace' => $this->replaceNameSpace($this->config->getNamespace('repository')),
            'routeDocsNamespace' => $this->replaceNameSpace($this->config->getNamespace('routeDocs')),
            'transformInterfaceNamespace' => $this->replaceNameSpace($this->config->getNamespace('transformInterface')),
            'transformerNamespace' => $this->replaceNameSpace($this->config->getNamespace('transformer')),
            'testRouteNamespace' => $this->replaceNameSpace($this->config->getNamespace('testRoute')),
            'testUnitNamespace' => $this->replaceNameSpace($this->config->getNamespace('testUnit')),
            //
            'fieldsDto' => $this->fieldsDto,
            'fieldsFaker' => $this->fieldsFaker,
            //
            'options' => $this->options ?? [],
        ];
    }

    function render() {
        return view($this->getFile(), [...$this->getRootData(), ...$this->getData()])->render();
    }

    abstract public function getFile(): string;
    abstract public function getData();
}
