<?php

namespace Devesharp\Generators\Common;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;
use function Termwind\render;

class TemplateData extends DataTransferObject
{
    /**
     * @var string $namespaceApp namespace do template
     */
    public string $namespaceApp = "";

    /**
     * @var string $moduleName nome do modulo
     */
    public string $moduleName = "";

    /**
     * @var string $moduleName nome do modulo
     */
    public string $resourceName = "";

    /**
     * @var string $resourceGramaticalName nome gramatical do recurso
     */
    public string $resourceGramaticalName = "";

    /**
     * @var string $routeName URI do recurso
     */
    public string $resourceURI = "";

    /**
     * @var string $tableName nome da tabela do recurso
     */
    public string $tableName = "";

    /**
     * Se o recurso já existe ou se está sendo criado junto
     */
    public bool $withController = false;
    public bool $withDto = false;
    public bool $withService = false;
    public bool $withMigration = false;
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

    /**
     * Namespaces dos outros layers
     */
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
    public string $testRouteNamespace = "";
    public string $testUnitNamespace = "";
    public string $userModelNamespace = "";

    /**
     * @var array importações do arquivo
     */
    public array $imports = [];

    /**
     * @var array campo fields dentro do arquivo
     */
    public array $fieldsRaw = [];

    /**
     * @var array campos para serem usados em CreateResourceDTO
     */
    public array $fieldsDto = [];

    /**
     * @var array campos para serem usados em SearchResourceDTO
     */
    public array $fieldsDtoSearch = [];

    /**
     * @var array campos para serem usados no Transformer
     */
    public array $fieldsTransformer = [];

    /**
     * @var array campos para serem usados na geração do Faker
     */
    public array $fieldsFaker = [];

    /**
     * @var array todas as colunas para ser usado na geração do migration
     */
    public array $columnsMigration = [];

    /**
     * @var array relação de colunas para ser usado na geração do migration
     */
    public array $relationColumnsMigration = [];

    /**
     * @var array campos para serem usados na varíavel $casts do model
     */
    public array $fieldsModelCasts = [];

    /**
     * @var array campos para ser usado na geração do PHPDocs (atualmente apenas no Model)
     */
    public array $fieldsPropertyPHPDocs = [];

    /**
     * @var array funções de relacionamento usado no Model
     */
    public string $modelRelationsFunctions = '';

    /**
     * @var array relações utilizadas no resource
     */
    public array $fieldsUsedOnResource = [];

    public function __construct(...$args)
    {
        parent::__construct($args);
    }

    function addImport($string) {
        if (empty($string)) return;

        $string = Blade::render($string, $this->toArray());
        foreach ($this->imports as $import) {
            if (Str::contains($import, $string)) {
                return;
            }
        }

        $this->imports[] = $string;
    }

    /**
     * @param string $file
     * @param $overData
     * @return static
     * @throws \Exception
     */
    static public function makeByFile(string $file, $overData = []): self {
        $templateData = new self();

        if (file_exists($file)) {
            $fileData = \yaml_parse(file_get_contents($file));
        } else {
            throw new \Exception("Arquivo não encontrado: {$file}");
        }

        $templateData->moduleName = $fileData['module'] ?? $fileData['name'] ?? '';
        $templateData->resourceName = $fileData['name'] ?? $fileData['module'] ?? '';

        $templateData->withController = $fileData['layers']['controller'] ?? true;
        $templateData->withDto = $fileData['layers']['dto']  ?? true;
        $templateData->withService = $fileData['layers']['service']  ?? true;
        $templateData->withMigration = $fileData['layers']['migration']  ?? true;
        $templateData->withFactory = $fileData['layers']['factory']  ?? true;
        $templateData->withModel = $fileData['layers']['model']  ?? true;
        $templateData->withPolicy = $fileData['layers']['policy']  ?? true;
        $templateData->withPresenter = $fileData['layers']['presenter']  ?? true;
        $templateData->withRepository = $fileData['layers']['repository']  ?? true;
        $templateData->withRouteDocs = $fileData['layers']['routeDocs']  ?? true;
        $templateData->withTransformerInterface = $fileData['layers']['transformerInterface']  ?? true;
        $templateData->withTransformer = $fileData['layers']['transformer']  ?? true;
        $templateData->withTestRoute = $fileData['layers']['testRoute']  ?? true;
        $templateData->withTestUnit = $fileData['layers']['testUnit']  ?? true;

        $templateData->fieldsRaw = $fileData['fields'] ?? [];

        if ($templateData->fieldsRaw) {
            $fieldGenerator = new TemplateFieldsGenerator();
            $templateData->fieldsDto = $fieldGenerator->getFieldsForDto($templateData);
            $templateData->fieldsDtoSearch = $fieldGenerator->getFieldsForDtoSearch($templateData);
            $templateData->fieldsTransformer = $fieldGenerator->getFieldsForTransformer($templateData);
            $templateData->fieldsFaker = $fieldGenerator->getFieldsForFaker($templateData);
            $templateData->columnsMigration = $fieldGenerator->getColumnsForMigration($templateData);
            $templateData->relationColumnsMigration = $fieldGenerator->getRelationsColumnsForMigration($templateData);
            $templateData->fieldsModelCasts = $fieldGenerator->getFieldsForCasts($templateData);
            $templateData->fieldsPropertyPHPDocs = $fieldGenerator->getPropertyPHPDocs($templateData);
            $templateData->modelRelationsFunctions = $fieldGenerator->getModelRelationFunctions($templateData);
            $templateData->fieldsUsedOnResource = $fieldGenerator->getFieldsUsedOnResource($templateData);
        }

        return $templateData;
    }


}
