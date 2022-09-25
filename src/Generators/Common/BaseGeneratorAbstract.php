<?php

namespace Devesharp\Generators\Common;

//    ds:generator $typeName $module $name
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Web64\Colors\Facades\Colors;

abstract class BaseGeneratorAbstract
{
    public array $options = [];

    public $command;

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
    public array $fieldsDtoSearch = [];
    public array $fieldsTransformer = [];
    public array $fieldsFaker = [];
    public array $fieldsMigration = [];
    public array $fieldsCasts = [];
    public array $propertyPHPDocs = [];
    //
    public string $fileTemplate = '';

    public function __construct(protected GeneratorConfig $config, protected FileSystem $fileSystem)
    {
        $config->init();
    }

    public function setData(array $data, array $options = []): self
    {
        $this->fileTemplate = $data['file_template'] ?? '';
        $this->moduleName = $data['module'];
        $this->resourceName = $data['name'] ?? $data['module'];
        $this->withCreate = $data['withCreate'] ?? true;
        $this->withUpdate = $data['withUpdate'] ?? true;
        $this->withDelete = $data['withDelete'] ?? true;
        $this->withSearch = $data['withSearch'] ?? true;
        $this->routeName = Str::slug(Str::snake($data['routeName'] ?? $this->resourceName));
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
        $this->fieldsDtoSearch = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForDtoSearch() : [];
        $this->fieldsTransformer = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForTransformer() : [];
        $this->fieldsFaker = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForFaker() : [];
        $this->fieldsMigration = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForMigration() : [];
        $this->fieldsCasts = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getFieldsForCasts() : [];
        $this->propertyPHPDocs = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getPropertyPHPDocs() : [];

        //
        $this->modelRelations = !empty($data['file_template']) ? (new FileTemplateManager($data['file_template']))->getModelRelationFunctions($this->replaceString($this->config->getNamespace('model'))) : '';

        $this->options = $options;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->replaceString($this->config->getNamespace(Str::camel($this->resourceType)));
    }

    public function getPath(): string
    {
        return $this->replaceString($this->config->getPath(Str::camel($this->resourceType)));
    }

    public function getSuffix(): string
    {
        return $this->replaceString($this->config->getSuffix(Str::camel($this->resourceType)));
    }

    public function getPrefix(): string
    {
        return $this->replaceString($this->config->getPrefix(Str::camel($this->resourceType)));
    }

    function replaceString(string $namespace, $moduleName = null, $resourceName = null): string
    {
        $namespace = str_replace('{{ModuleName}}', $moduleName ?? $this->moduleName, $namespace);
        $namespace = str_replace('{{Resource}}', $resourceName ?? $this->resourceName, $namespace);

        return $namespace;
    }

    function getRootData() {
        return [
            'namespaceApp' => $this->getNamespace(),
            'moduleName' => $this->moduleName,
            'resourceName' => $this->resourceName,
            'resourceNameForDocs' => $this->resourceName,
            'routeName' => $this->routeName,
            'tableName' => Str::snake(trim($this->resourceName)),
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
            'controllerNamespace' => $this->replaceString($this->config->getNamespace('controller')),
            'dtoNamespace' => $this->replaceString($this->config->getNamespace('dto')),
            'serviceNamespace' => $this->replaceString($this->config->getNamespace('service')),
            'factoryNamespace' => $this->replaceString($this->config->getNamespace('factory')),
            'modelNamespace' => $this->replaceString($this->config->getNamespace('model')),
            'policyNamespace' => $this->replaceString($this->config->getNamespace('policy')),
            'presenterNamespace' => $this->replaceString($this->config->getNamespace('presenter')),
            'repositoryNamespace' => $this->replaceString($this->config->getNamespace('repository')),
            'routeDocsNamespace' => $this->replaceString($this->config->getNamespace('routeDocs')),
            'transformerInterfaceNamespace' => $this->replaceString($this->config->getNamespace('transformerInterface')),
            'transformerNamespace' => $this->replaceString($this->config->getNamespace('transformer')),
            'testRouteNamespace' => $this->replaceString($this->config->getNamespace('testRoute')),
            'testUnitNamespace' => $this->replaceString($this->config->getNamespace('testUnit')),
            'userModelNamespace' => $this->replaceString($this->config->getNamespace('model'), 'Users'),
            //
            'fieldsDto' => $this->fieldsDto,
            'fieldsDtoSearch' => $this->fieldsDtoSearch,
            'fieldsTransformer' => $this->fieldsTransformer,
            'fieldsFaker' => $this->fieldsFaker,
            'fieldsMigration' => $this->fieldsMigration,
            'fieldsCasts' => $this->fieldsCasts,
            'propertyPHPDocs' => $this->propertyPHPDocs,
            //
            'modelRelations' => $this->modelRelations,
            //
            'options' => $this->options ?? [],
        ];
    }

    function render() {
        return view($this->getFile(), [...$this->getRootData(), ...$this->getData()])->render();
    }

    function getFileName() {
        $suffix = $this->getSuffix();
        $prefix = $this->getPrefix();
        return $prefix . Str::studly($this->moduleName) . $suffix . '.php';
    }

    function generate() {
        $content = $this->render();
        $filename = $this->getPath();
        $filename .= '/' . $this->getFileName();
        $baseFileName = str_replace(base_path(''), '', $filename);

        if (file_exists($filename)) {
            $this->infoExistFile($baseFileName);
            return;
        }

        try {
//            if (!file_exists(dirname($filename))) {
//                mkdir(dirname($filename), 0777, true);
//            }

            $this->fileSystem->writeFile($filename, $content);
//            file_put_contents($filename, $content);
            $this->infoCreateFile($baseFileName);
        }catch (\Exception $e) {
            $this->infoErrorFile($baseFileName);
            var_dump($e->getMessage());
        }
    }

    function setCommand($command) {
        $this->command = $command;
    }

    function error($string) {
//        $this->command->error($string);
    }

    function infoCreateFile($string) {
//        $this->command->line( '<info>CREATED</info>  ' . $string);
    }

    function infoErrorFile($string) {
//        $this->command->line( '<info>ERROR</info>    ' . $string);
    }

    function infoExistFile($string) {
//        $this->command->line( '<error>EXIST</error>  ' . $string);
    }

    function infoEditFile($string) {
//        $this->command->line( '<error>EDITED</error> ' . $string);
    }

    abstract public function getFile(): string;
    abstract public function getData();
}
