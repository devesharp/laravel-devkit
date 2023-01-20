<?php

namespace Devesharp\Generators\Common;

//    ds:generator $typeName $module $name
use Devesharp\Support\Collection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Web64\Colors\Facades\Colors;

class TemplateFieldsGenerator
{
    public function getFieldsForDto(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {

            $rules = [];
            switch (strtolower($field['dbType'])) {
                case 'foreign':
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                case 'double':
                case 'float':
                case 'decimal':
                    $rules = ['numeric_string'];
                    break;
                case 'string':
                case 'char':
                case 'text':
                    $rules = ['string'];
                    break;
                    break;
                case 'boolean':
                    $rules = ['bool'];
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $rules = ['string'];
                    break;
                default:
                    $rules = ['string'];
            }

            if (!empty($field['dto'])) {

                if (!empty($field['rules'])) {
                    $rules[] = $field['rules'];
                }

                $fields[] = [
                    'name' => $key,
                    'rules' => implode( '|', $rules),
                    'description' => $field['description'] ?? '',
                ];
            }
        }

        return $fields;
    }

    public function getFieldsForDtoSearch(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {

            $rules = [];
            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                case 'double':
                case 'float':
                case 'decimal':
                case 'id':
                    $rules = ['numeric_string'];
                    break;
                case 'string':
                case 'char':
                case 'text':
                    $rules = ['string'];
                    break;
                    break;
                case 'boolean':
                    $rules = ['bool'];
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $rules = ['string'];
                    break;
                default:
                    $rules = ['string'];
            }

            if (!empty($field['searchable'])) {

                if (!empty($field['rules'])) {
                    $removeRequireds = str_replace(['|required', 'required|', 'required'], '', $field['rules']);

                    if (!empty($removeRequireds)) {
                        $rules[] = $removeRequireds;
                    }
                }

                $fields[] = [
                    'name' => 'filters.' . $key,
                    'rules' => implode( '|', $rules),
                    'description' => $field['description'] ?? '',
                ];
            }
        }
        return $fields;
    }

    public function getFieldsForTransformer(TemplateData &$templateData)
    {
        $fields = [];

        foreach ($templateData->fieldsRaw as $key => $field) {

            $type = "string";
            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                    $type = 'number';
                    break;
                case 'double':
                case 'float':
                case 'decimal':
                    $type = 'float';
                    break;
                case 'string':
                case 'char':
                case 'text':
                    $type = 'string';
                    break;
                    break;
                case 'boolean':
                    $type = 'bool';
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $type = 'date';
                    break;
                default:
                    $type = 'string';
            }

            if (!empty($field['transformer'])) {
                $fields[] = [
                    'name' => $key,
                    'type' => $type,
                    'valueOnCreate' => !empty($field['valueOnCreate']),
                    'now' => !empty($field['valueOnCreate']['value']) && $field['valueOnCreate']['value'] == 'now',
                ];
            }
        }
        return $fields;
    }

    public function getFiltersSearchable(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $filterType = '';

            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                    $filterType = 'ServiceFilterEnum::whereInt';
                    break;
                case 'double':
                case 'float':
                case 'decimal':
                    $filterType = 'ServiceFilterEnum::whereNumber';
                    break;
                case 'string':
                case 'char':
                case 'text':
                    $filterType = 'ServiceFilterEnum::whereContainsExplodeString';
                    break;
                case 'bool':
                case 'boolean':
                    $filterType = 'ServiceFilterEnum::whereBoolean';
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $filterType = 'ServiceFilterEnum::whereDate';
                    break;
                default:
                    $filterType = 'ServiceFilterEnum::whereEqual';
            }

            if (!empty($field['searchable'])) {
                $fields[] = [
                    'name' => $key,
                    'filterType' => $filterType,
                ];

                switch (strtolower($field['dbType'])) {
                    case 'date':
                    case 'datetime':
                    case 'timestamp':
                    case 'time':
                        $fields[] = [
                            'name' => $key . '_gte',
                            'filterType' => 'ServiceFilterEnum::whereDateGte',
                        ];

                        $fields[] = [
                            'name' => $key . '_lte',
                            'filterType' => 'ServiceFilterEnum::whereDateLte',
                        ];

                        break;
                }
            }
        }
        return $fields;
    }

    public function getFiltersSort(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['sort'])) {
                $fields[] = [
                    'name' => $key
                ];
            }
        }
        return $fields;
    }

    public function getUsersServiceRelation(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['valueOnCreate'])) {
                $treatmentField = [
                    'fieldName' => $key,
                    'userFieldName' => $field['valueOnCreate']['getByUser'] ?? '',
                    'value' => $field['valueOnCreate']['value'] ?? '',
                    'valueRaw' => $field['valueOnCreate']['valueRaw'] ?? '',
                ];

                if (!empty($field['valueOnCreate']['value'])) {
                    if (is_numeric($field['valueOnCreate']['value'])) {
                        $treatmentField['value'] = $field['valueOnCreate']['value'];
                    } else {
                        if ($field['dbType'] == 'date' || $field['dbType'] == 'datetime' ||  $field['dbType'] == 'timestamp' ||  $field['dbType'] == 'time' && $field['valueOnCreate']['value'] == 'now') {
                            $treatmentField['value'] = "\Carbon\Carbon::now()";
                        } else {
                            $treatmentField['value'] = "'" . $field['valueOnCreate']['value'] . "'";
                        }
                    }
                }

                $fields[] = $treatmentField;
            }
        }

        return $fields;
    }

    public function getServiceValuesOnSearch(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['valueOnSearch'])) {
                $treatmentField = [
                    'fieldName' => $key,
                    'userFieldName' => $field['valueOnCreate']['getByUserKey'] ?? '',
                    'value' => $field['valueOnCreate']['value'] ?? '',
                    'valueRaw' => $field['valueOnCreate']['valueRaw'] ?? '',
                    'queryFn' => 'whereEqual',
                ];

                if (!empty($field['valueOnSearch']['value'])) {
                    if (is_numeric($field['valueOnSearch']['value'])) {
                        $treatmentField['value'] = $field['valueOnSearch']['value'];
                        $treatmentField['queryFn'] = 'whereInt';
                    } else {
                        if ($field['dbType'] == 'date' || $field['dbType'] == 'datetime' ||  $field['dbType'] == 'timestamp' ||  $field['dbType'] == 'time' && $field['valueOnSearch']['value'] == 'now') {
                            $treatmentField['value'] = "\Carbon\Carbon::now()";
                            $treatmentField['queryFn'] = 'whereDate';
                        } else {
                            $treatmentField['value'] = "'" . $field['valueOnSearch']['value'] . "'";
                            $treatmentField['queryFn'] = 'whereSameString';
                        }
                    }
                }

                $fields[] = $treatmentField;
            }
        }
        return $fields;
    }

    public function getFieldsForFaker(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $fakerFn = '""';
            $request = !empty($field['dto']);

            if (!empty($field['relation'])) {
                $fakerFn = '1';

                if (!empty($field['nullable'])) {
                    continue;
                }
            } else {
                switch (strtolower($field['dbType'])) {
                    case 'integer':
                    case 'unsignedinteger':
                    case 'smallinteger':
                    case 'biginteger':
                    case 'unsignedbiginteger':
                    case 'long':
                        $fakerFn = 'fake()->randomNumber';
                        break;
                    case 'double':
                    case 'float':
                    case 'decimal':
                        $fakerFn = 'fake()->randomFloat(2)';
                        break;
                    case 'string':
                    case 'char':
                    case 'text':
                        $fakerFn = 'fake()->text(100)';
                        break;
                    case 'bool':
                    case 'boolean':
                        $fakerFn = 'fake()->boolean()';
                        break;
                    case 'date':
                        $fakerFn = "fake()->date('Y-m-d')";
                        break;
                    case 'datetime':
                    case 'timestamp':
                        $fakerFn = "fake()->date('Y-m-d H:i:s')";
                        break;
                    case 'time':
                        $fakerFn = "fake()->date('H:i:s')";
                        break;
                    case 'enum':
                        $fieldsString = Collection::make($field['acceptableValues'])
                            ->map(fn($items) => "'$items'")
                            ->map(fn($item) => is_numeric($item) ? floatval($item) : $item)->implode(',');
                        $fakerFn = "fake()->randomElement([".$fieldsString."])";
                        break;
                    default:
                        if (Str::contains($field['dbType'], 'foreign')) {
                            $fakerFn = 1;
                            $request = false;
                        } else {
                            $fakerFn = 'fake()->text(100)';
                        }
                }

                if ($key == "CPF" || $key == "cpf") {
                    $fakerFn = 'fake()->numerify("###.###.###-##")';
                }

                if ($key == "document" || $key == "RG") {
                    $fakerFn = 'fake()->numerify("##.###.###-#")';
                }

                if ($key == "enabled") {
                    $fakerFn = 'true';
                    $request = false;
                }
            }

            if (empty($field['primary']) && $key !== 'created_at' && $key !== 'updated_at') {
                $fields[] = [
                    'name' => $key,
                    'faker_function' => $fakerFn,
                    'request' => $request,
                ];
            }
        }
        return $fields;
    }

    public function getPropertyPHPDocs(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $type = '';
            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                    $type = 'integer';
                    break;
                case 'double':
                case 'float':
                case 'decimal':
                    $type = 'double';
                    break;
                case 'string':
                case 'char':
                case 'text':
                    $type = 'string';
                    break;
                case 'bool':
                case 'boolean':
                    $type = 'bool';
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $type = "Carbon";
                    // Add Carbon to use
                    $templateData->addImport('\Illuminate\Support\Carbon');
                    break;
                default:
                    $type = 'string';
            }

            if (empty($field['primary']) && $key !== 'created_at' && $key !== 'updated_at') {
                $fields[] = [
                    'name' => $key,
                    'type' => $type,
                    'description' => $field['description'] ?? '',
                ];
            }
        }
        return $fields;
    }

    public function getModelRelationFunctions(TemplateData &$templateData): string
    {
        $relations = '';

        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['relation'])) {
                $typeRelation = $field['relation']['type'];

                $tableForeign = $field['relation']['resource'];
                $singularRelation = "relation" . Str::camel(Str::singular($key));
                $pluralRelation = Str::camel(Str::plural($tableForeign));
                $primaryKeyName = $field['relation']['key'];

                if (str_replace('_id', '', $key) != $singularRelation && substr($key, -2) != "Id") {
                    $singularRelation = "relation" . Str::studly(str_replace('_id', '', $key));
                }

                switch ($typeRelation) {
                    case '1t1':
                        $functionName = $singularRelation;
                        $relation = 'hasOne';
                        $relationClass = 'HasOne';
                        break;
                    case '1tm':
                        $functionName = $pluralRelation;
                        $relation = 'hasMany';
                        $relationClass = 'HasMany';
                        break;
                    case 'mt1':
                        $functionName = $singularRelation;
                        $relation = 'belongsTo';
                        $relationClass = 'BelongsTo';
                        break;
                    case 'mtm':
                        $functionName = $pluralRelation;
                        $relation = 'belongsToMany';
                        $relationClass = 'BelongsToMany';
                        break;
                    case 'hmt':
                        $functionName = $pluralRelation;
                        $relation = 'hasManyThrough';
                        $relationClass = 'HasManyThrough';
                        break;
                    default:
                        $functionName = '';
                        $relation = '';
                        $relationClass = '';
                        break;
                }

                // Trocar por nome modulo da relação
                $config = app(GeneratorConfig::class);
                $config->init();
                $modelNamespace = $config->modelNamespace;
                $modelNamespace = str_replace('{{ModuleName}}', $field['relation']['module'] ?? $field['relation']['resource'], $modelNamespace);

                if (!empty($functionName)) {
                    $relations .= "\n";
                    if ($relation == 'belongsTo') {
                        $relations .= "    public function $functionName(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\\$modelNamespace\\$tableForeign {";
                    }else {
                        $relations .= "    public function $functionName():  {";
                    }
                    $relations .= "\n";
                    $relations .= "        return \$this->$relation(\\$modelNamespace\\$tableForeign::class, '$key', '$primaryKeyName');";
                    $relations .= "\n";
                    $relations .= "    }";
                    $relations .= "\n";
                }
            }

        }

        return $relations;
    }

    public function getFieldsUsedOnResource(TemplateData &$templateData)
    {
        $relations = [];

        /** @var GeneratorConfig $config */
        $config = app(GeneratorConfig::class);
        $config->init();

        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['relation'])) {
                $typeRelation = $field['relation']['type'];
                $tableForeign = $field['relation']['resource'];
                $moduleTableForeign = $field['relation']['module'] ?? $field['relation']['resource'];
                $singularRelation = Str::camel(Str::singular($tableForeign));
                $pluralRelation = Str::camel(Str::plural($tableForeign));
                $primaryKeyName = $field['relation']['key'];

                $relationsConfig = config('devesharp_dev_kit.relations', []);
                $usedUserRelation = $tableForeign == 'Users';
                $namespaceModel = $config->getNamespace('model');
                $namespaceModel = str_replace('{{ModuleName}}', $moduleTableForeign, $namespaceModel);

                foreach ($relationsConfig as $relation) {
                    foreach ($relation as $item) {
                        if ($item['resource'] == $tableForeign) {
                            $usedUserRelation = true;
                        }
                    }
                }

                $relations[] = [
                    /**
                     * As vezes o usuário irá usar a mesma relação que o recurso atual
                     * Por exemplo Platforms
                     * Usuário irá ter platform_id e um tabela qualquer 'posts' também terá platform_id
                     * Por isso não é necessário criar o factory mais 1 vez, deve ser usado o mesmo
                     */
                    'usedUserRelation' => $usedUserRelation,
                    'alreadyBeenDefined' => collect($relations)->some(fn($item) => $item['resourceName'] == $tableForeign),
                    'namespace' => $namespaceModel . '\\' . $tableForeign,
                    'resourceName' => $tableForeign,
                    'localKey' => $key,
                    'variable' => $singularRelation,
                    'key' => $primaryKeyName,
                    'valueOnCreate' => !empty($field['valueOnCreate']),
                    'transformer' => !empty($field['transformer']),
                ];

                $templateData->addImport($namespaceModel . '\\' . $tableForeign);
            }

        }

        return $relations;
    }

    public function getFieldsForCasts(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $cast = '';
            switch (strtolower($field['dbType'])) {
                case 'json':
                    $cast = 'array';
                    break;
                case 'bool':
                case 'boolean':
                    $cast = 'bool';
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                case 'time':
                    $cast = "date";
                    break;
            }

            if ($key == 'enabled') {
                $fields[] = [
                    'name' => 'deleted_at',
                    'cast' => 'date',
                ];
            }

            if (!empty($cast)) {
                $fields[] = [
                    'name' => $key,
                    'cast' => $cast,
                ];
            }
        }
        return $fields;
    }

    public function getColumnsForMigration(TemplateData &$templateData)
    {
        $fields = [];
        $createdAtField = null;
        $updatedAtField = null;
        $enabledAtField = null;
        foreach ($templateData->fieldsRaw as $key => $field) {
            if ($key == 'enabled') {
                $enabledAtField = $field;
            }
        }

        foreach ($templateData->fieldsRaw as $key => $field) {
            if ($key == 'created_at') {
                $createdAtField = $field;
                continue;
            } else if ($key == 'updated_at') {
                $updatedAtField = $field;
                continue;
            } else if ($key == 'enabled') {
                $enabledAtField = $field;
            }

            if ($enabledAtField && $key == 'deleted_at') {
                continue;
            }

            if (Str::contains($field['dbType'], 'foreign')) {
                $migrationField = '$table->unsignedBigInteger(\'' . $key . '\')';
            } else if (Str::contains($field['dbType'], 'enum')) {
                $fieldsString = Collection::make($field['acceptableValues'])->map(fn($items) => "'$items'")->map(fn($item) => is_numeric($item) ? floatval($item) : $item)->implode(',');
                $migrationField = '$table->enum(\'' . $key . '\', [' . $fieldsString . '])';
            }  else {
                if ($field['dbType'] == "id" && $key == "id") {
                    $migrationField = '$table->' . $field['dbType'] . '()';
                } else {
                    $migrationField = '$table->' . $field['dbType'] . '(\'' . $key . '\')';
                }
            }

            if (isset($field['default'])) {
                if (is_bool($field['default'])) {
                    $migrationField .= '->default(' . ($field['default'] ? 'true' : 'false') . ')';
                } else if (is_numeric($field['default'])) {
                    $migrationField .= '->default(' . $field['default'] . ')';
                } else {
                    $migrationField .= '->default(\'' . $field['default'] . '\')';
                }
            }

            if ($field['dbType'] != "id" && !empty($field['primary'])) {
                $migrationField .= '->primary()';
            }

            if (!empty($field['nullable'])) {
                $migrationField .= '->nullable()';
            }

            $migrationField .= ';';

            $fields[] = $migrationField;
        }

        if (!empty($enabledAtField)) {
            $fields[] = '$table->softDeletes();';
        }

        if ($createdAtField['dbType'] === 'timestamp' and $updatedAtField['dbType'] === 'timestamp') {
            $fields[] = '$table->timestamps();';
        } else {
            if ($createdAtField) {
                $fields[] = $createdAtField->migrationText;
            }
            if ($updatedAtField) {
                $fields[] = $updatedAtField->migrationText;
            }
        }

        return $fields;
    }

    public function getRelationsColumnsForMigration(TemplateData &$templateData)
    {
        $foreignKeys = [];

        foreach ($templateData->fieldsRaw as $key => $field) {
            if (!empty($field['relation'])) {
                $foreignTable = Str::snake(trim($field['relation']['resource']));
                $foreignField = $field['relation']['key'] ?? 'id';

                $foreignKeys[] = "\$table->foreign('".$key."')->references('".$foreignField."')->on('".$foreignTable."');";
            }
        }

        return $foreignKeys;
    }
}