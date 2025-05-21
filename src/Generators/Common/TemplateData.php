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
     * @var string $resourceNameUpperSnake nome do modulo
     */
    public string $resourceNameUpperSnake = "";

    /**
     * @var string $resourceGramaticalName nome gramatical do recurso
     */
    public string $resourceGramaticalName = "";

    /**
     * @var string $resourceURI URI do recurso
     */
    public string $resourceURI = "";

    /**
     * @var string $tableName nome da tabela do recurso
     */
    public string $tableName = "";

    /**
     * @var string hora atual Y_m_d_His para criar o nome do arquivo migration
     */
    public string $now = "";

    /**
     * Se o recurso já existe ou se está sendo criado junto
     */
    public bool $withController = false;
    public bool $withRoutes = false;
    public bool $withDto = false;
    public bool $withService = false;
    public bool $withMigration = false;
    public bool $withFactory = false;
    public bool $withPermission = false;
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
    public array $fieldsFakerDefinition = [];

    /**
     * @var array campos para serem usados na geração do Faker para docs
     * Deixar os campos mais "bonitos", ao invés lorem ipsum
     */
    public array $fieldsFakerForDocs = [];

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

    /**
     * @var array valores usados no $filters no Service
     */
    public array $filtersSearchable = [];

    /**
     * @var array valores usados no $sorts no Service
     */
    public array $filtersSort = [];

    /**
     * @var array valores usados ao criar recurso no Service
     */
    public array $valueOnCreate = [];

    /**
     * @var array valores usados ao criar recurso no Service
     */
    public array $valueOnUpdate = [];

    /**
     * @var array valores usados ao buscar recurso no Service
     */
    public array $valueOnSearch = [];

    public function __construct(...$args)
    {
        parent::__construct($args);
    }

    function addImport($string) {
        if (empty($string)) return;

        $string = Blade::render($string, $this->toArray());
        foreach ($this->imports as $import) {
            if ($import == $string) {
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
        $templateData->resourceGramaticalName = $fileData['gramatical_name'] ?? $templateData->resourceName;

        $templateData->withController = $fileData['layers']['withController'] ?? true;
        $templateData->withRoutes = $fileData['layers']['withRoutes'] ?? true;
        $templateData->withPermission = $fileData['layers']['withPermission'] ?? true;
        $templateData->withDto = $fileData['layers']['withDto']  ?? true;
        $templateData->withService = $fileData['layers']['withService']  ?? true;
        $templateData->withMigration = $fileData['layers']['withMigration']  ?? true;
        $templateData->withFactory = $fileData['layers']['withFactory']  ?? true;
        $templateData->withModel = $fileData['layers']['withModel']  ?? true;
        $templateData->withPolicy = $fileData['layers']['withPolicy']  ?? true;
        $templateData->withPresenter = $fileData['layers']['withPresenter']  ?? true;
        $templateData->withRepository = $fileData['layers']['withRepository']  ?? true;
        $templateData->withRouteDocs = $fileData['layers']['withRouteDocs']  ?? true;
        $templateData->withTransformerInterface = $fileData['layers']['withTransformerInterface']  ?? true;
        $templateData->withTransformer = $fileData['layers']['withTransformer']  ?? true;
        $templateData->withTestRoute = $fileData['layers']['withTestRoute']  ?? true;
        $templateData->withTestUnit = $fileData['layers']['withTestUnit']  ?? true;

        $templateData->fieldsRaw = $fileData['fields'] ?? [];

        if ($templateData->fieldsRaw) {
            $fieldGenerator = new TemplateFieldsGenerator();
            $templateData->fieldsDto = $fieldGenerator->getFieldsForDto($templateData);
            $templateData->fieldsDtoSearch = $fieldGenerator->getFieldsForDtoSearch($templateData);
            $templateData->fieldsTransformer = $fieldGenerator->getFieldsForTransformer($templateData);
//            var_dump($templateData->fieldsTransformer);
            $templateData->fieldsFaker = $fieldGenerator->getFieldsForFaker($templateData);
            $templateData->fieldsFakerDefinition = $fieldGenerator->getFieldsForFakerDefinition($templateData);
            $templateData->fieldsFakerForDocs = $fieldGenerator->getFieldsForFakerDocs($templateData);
            $templateData->columnsMigration = $fieldGenerator->getColumnsForMigration($templateData);
            $templateData->relationColumnsMigration = $fieldGenerator->getRelationsColumnsForMigration($templateData);
            $templateData->fieldsModelCasts = $fieldGenerator->getFieldsForCasts($templateData);
            $templateData->fieldsPropertyPHPDocs = $fieldGenerator->getPropertyPHPDocs($templateData);
            $templateData->modelRelationsFunctions = $fieldGenerator->getModelRelationFunctions($templateData);
            $templateData->fieldsUsedOnResource = $fieldGenerator->getFieldsUsedOnResource($templateData);
            $templateData->filtersSearchable = $fieldGenerator->getFiltersSearchable($templateData);
            $templateData->filtersSort = $fieldGenerator->getFiltersSort($templateData);
            $templateData->valueOnCreate = $fieldGenerator->getUsersServiceRelation($templateData);
            $templateData->valueOnUpdate = $fieldGenerator->getUsersUpdateValues($templateData);
            $templateData->valueOnSearch = $fieldGenerator->getServiceValuesOnSearch($templateData);
        }

        return $templateData;
    }


}
