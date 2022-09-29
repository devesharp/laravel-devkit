<?php

namespace Devesharp\Generators\Common;

//    ds:generator $typeName $module $name
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Web64\Colors\Facades\Colors;

abstract class TemplateGenerator
{
    public string $fileTemplate = '';

    public string $moduleName = '';

    public string $resourceType = '';

    public string $resourceName = '';

    public string $resourceGramaticalName = '';

    public TemplateData $templateData;

    public array $additionalData = [];

    public function __construct(protected GeneratorConfig $config, protected FileSystem $fileSystem, protected FileTemplateManager $fileTemplateManager)
    {
        $config->init();
    }

    /**
     * @return string
     */
    public function getFileTemplate(): string
    {
        return $this->fileTemplate;
    }

    /**
     * @param string $fileTemplate
     * @return TemplateGenerator
     */
    public function setFileTemplate(string $fileTemplate): TemplateGenerator
    {
        $this->fileTemplate = $fileTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     * @return TemplateGenerator
     */
    public function setModuleName(string $moduleName): TemplateGenerator
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @param string $resourceType
     * @return TemplateGenerator
     */
    public function setResourceType(string $resourceType): TemplateGenerator
    {
        $this->resourceType = $resourceType;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * @param string $resourceName
     * @return TemplateGenerator
     */
    public function setResourceName(string $resourceName): TemplateGenerator
    {
        $this->resourceName = $resourceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceGramaticalName(): string
    {
        return $this->resourceGramaticalName;
    }

    /**
     * @param string $resourceGramaticalName
     * @return TemplateGenerator
     */
    public function setResourceGramaticalName(string $resourceGramaticalName): TemplateGenerator
    {
        $this->resourceGramaticalName = $resourceGramaticalName;
        return $this;
    }

    /**
     * @return TemplateData
     */
    public function getTemplateData(): TemplateData
    {
        return $this->templateData;
    }

    /**
     * @param TemplateData $templateData
     */
    public function setTemplateData(TemplateData $templateData): void
    {
        $this->moduleName = $templateData->moduleName ?? $templateData->resourceName;
        $this->resourceName = $templateData->resourceName ?? $templateData->moduleName;

        $templateData->namespaceApp = $this->getNamespace();
        $templateData->resourceName = $this->resourceName;
        $templateData->resourceURI = Str::slug(Str::snake($data['routeName'] ?? $this->resourceName));
        $templateData->tableName = Str::snake(trim($this->resourceName));
        $templateData->resourceGramaticalName = !empty($templateData->resourceGramaticalName) ? $templateData->resourceGramaticalName : $this->resourceName;

//        $templateData->resourceNameForDocs = $this->resourceName;

        $templateData->controllerNamespace = $this->replaceString($this->config->getNamespace('controller'));
        $templateData->dtoNamespace = $this->replaceString($this->config->getNamespace('dto'));
        $templateData->serviceNamespace = $this->replaceString($this->config->getNamespace('service'));
        $templateData->factoryNamespace = $this->replaceString($this->config->getNamespace('factory'));
        $templateData->modelNamespace = $this->replaceString($this->config->getNamespace('model'));
        $templateData->policyNamespace = $this->replaceString($this->config->getNamespace('policy'));
        $templateData->presenterNamespace = $this->replaceString($this->config->getNamespace('presenter'));
        $templateData->repositoryNamespace = $this->replaceString($this->config->getNamespace('repository'));
        $templateData->routeDocsNamespace = $this->replaceString($this->config->getNamespace('routeDocs'));
        $templateData->transformerInterfaceNamespace = $this->replaceString($this->config->getNamespace('transformerInterface'));
        $templateData->transformerNamespace = $this->replaceString($this->config->getNamespace('transformer'));
        $templateData->testRouteNamespace = $this->replaceString($this->config->getNamespace('testRoute'));
        $templateData->testUnitNamespace = $this->replaceString($this->config->getNamespace('testUnit'));
        $templateData->userModelNamespace = $this->replaceString($this->config->getNamespace('model'), 'Users');

        // Converter
        $templateData->userModelNamespace = $this->replaceString($this->config->getNamespace('model'), 'Users');

        $templateData->modelRelationsFunctions = Blade::render($templateData->modelRelationsFunctions, $templateData->toArray());

        $this->templateData = $templateData;
    }

    /**
     * Renderiza o template
     *
     * @return string
     */
    function render(): string
    {
        // Carregar importações
        $this->loadImports();

        return view($this->getTemplateFilename(), [...$this->templateData->toArray(), ...$this->additionalData, ...$this->getData()])->render();
    }

    function loadImports(): void
    {

    }

    /**
     * Renderizar e salvar arquivo
     *
     * @return void
     */
    function handle()
    {
        $content = $this->render();
        $filename = $this->getPath();
        $filename .= '/' . $this->getFileName();
        $baseFileName = str_replace(base_path(''), '', $filename);

        if (file_exists($filename)) {
//            $this->infoExistFile($baseFileName);
            return;
        }

        try {
//            if (!file_exists(dirname($filename))) {
//                mkdir(dirname($filename), 0777, true);
//            }

            $this->fileSystem->writeFile($filename, $content);
//            $this->infoCreateFile($baseFileName);
        }catch (\Exception $e) {
//            $this->infoErrorFile($baseFileName);
            var_dump($e->getMessage());
        }
    }

    /**
     * Resgatar nome do arquivo
     *
     * @return string
     */
    function getFileName()
    {
        $suffix = $this->replaceString($this->config->getSuffix(Str::camel($this->resourceType)));
        $prefix = $this->replaceString($this->config->getPrefix(Str::camel($this->resourceType)));
        return $prefix . Str::studly($this->resourceName) . $suffix . '.php';
    }

    /**
     * Resgatar namespace
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->replaceString($this->config->getNamespace(Str::camel($this->resourceType)));
    }

    /**
     * Resgatar caminho
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->replaceString($this->config->getPath(Str::camel($this->resourceType)));
    }

    /**
     * Trocar valores por nome do modulo e do recurso
     *
     * @param string $namespace
     * @param $moduleName
     * @param $resourceName
     * @return string
     */
    function replaceString(string $namespace, $moduleName = null, $resourceName = null): string
    {
        $namespace = str_replace('{{ModuleName}}', $moduleName ?? $this->moduleName, $namespace);
        $namespace = str_replace('{{Resource}}', $resourceName ?? $this->resourceName, $namespace);

        return $namespace;
    }

    /**
     * Resgatar localização do template
     *
     * @return string
     */
    abstract public function getTemplateFilename(): string;

    /**
     * Informar dados que serão substituidos no template
     *
     * @return mixed
     */
    abstract public function getData(): array;
}