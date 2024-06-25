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
                    if (!empty($field['format'])) {
                        $rules = ['date_format:'.$field['format']];
                    } else {
                        $rules = ['string'];
                    }
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
            $subType = "";

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

            $keyLower = strtolower($key);

            if ($keyLower == 'cpf' || $keyLower == 'cnpj_or_cpf' || $keyLower == 'cnpj' || $keyLower == 'cnpj' || $keyLower == 'document' || Str::contains($keyLower, 'cpf') || Str::contains($keyLower, 'cnpj')) {
                $subType = 'cpf';
            }

            if (Str::contains($keyLower, 'phone') || Str::contains($keyLower, 'celular') || Str::contains($keyLower, 'telefone')) {
                $subType = 'phone';
            }

            if ($keyLower == 'rg') {
                $subType = 'rg';
            }

            if (Str::contains($keyLower, 'cep') || Str::contains($keyLower, 'postal_code') || Str::contains($keyLower, 'zip_postal')) {
                $subType = 'cep';
            }


            if (!empty($field['transformer'])) {
                $fields[] = [
                    'name' => $key,
                    'type' => $type,
                    'dto' => !empty($field['dto']),
                    'subType' => $subType,
                    'format' => $field['format'] ?? '',
                    'nullable' => !empty($field['nullable']),
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
            $keyLower = strtolower($key);

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

    public function getUsersUpdateValues(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $keyLower = strtolower($key);

            $removeLetter = $keyLower == 'cpf' || $keyLower == 'cnpj_or_cpf' || $keyLower == 'cnpj' || $keyLower == 'cnpj' || $keyLower == 'document' || Str::contains($keyLower, 'cpf') || Str::contains($keyLower, 'cnpj') ||
Str::contains($keyLower, 'phone') || Str::contains($keyLower, 'celular') || Str::contains($keyLower, 'telefone') ||
Str::contains($keyLower, 'cep') || Str::contains($keyLower, 'postal_code') || Str::contains($keyLower, 'zip_postal');

            if ($removeLetter) {
                $treatmentField = [
                    'fieldName' => $key,
                    'value' => 'format(OnlyLettersNumbersFormatter::class, $requestData["'.$key.'"])',
                ];

                $fields[] = $treatmentField;
                continue;
            }

            $isDate = $field['dbType'] == 'date';

            if ($isDate && !empty($field['format'])) {
                $treatmentField = [
                    'fieldName' => $key,
                    'value' => 'Carbon::createFromFormat("'.$field['format'].'", $requestData["'.$key.'"])->format("Y-m-d")',
                ];

                $fields[] = $treatmentField;
                continue;
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
                    'userFieldName' => $field['valueOnCreate']['getByUser'] ?? '',
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

            if ($key == 'deleted_at') {
                $fakerFn = 'null';
            }else if (!empty($field['relation'])) {
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
                        if ($key == 'deleted_at') {
                            $fakerFn = 'fake()->boolean()';
                        }else {
                            $fakerFn = 'fake()->boolean()';
                        }
                        break;
                    case 'date':
                        if (empty($field['format'])) {
                            $fakerFn = "fake()->date('Y-m-d')";
                        } else {
                            $fakerFn = "fake()->date('".$field['format']."')";
                        }

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

                $keyLower = strtolower($key);

                if ($keyLower == 'cpf' || $keyLower == 'cnpj_or_cpf' || $keyLower == 'cnpj' || $keyLower == 'cnpj' || $keyLower == 'document' || Str::contains($keyLower, 'cpf') || Str::contains($keyLower, 'cnpj')) {
                    $fakerFn = 'fake()->numerify("###.###.###-##")';
                }

                if (Str::contains($keyLower, 'phone') || Str::contains($keyLower, 'celular') || Str::contains($keyLower, 'telefone')) {
                    $fakerFn = 'fake()->numerify("(##) #####-####")';
                }

                if ($keyLower == 'rg') {
                    $fakerFn = 'fake()->numerify("##.###.###-#")';
                }

                if (Str::contains($keyLower, 'cep') || Str::contains($keyLower, 'postal_code') || Str::contains($keyLower, 'zip_postal')) {
                    $fakerFn = 'fake()->numerify("#####-###")';
                }

                if ($key == 'email' || Str::contains($keyLower, 'email')) {
                    $fakerFn = 'fake()->email()';
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

    public function getFieldsForFakerDefinition(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            $fakerFn = '""';
            $request = !empty($field['dto']);

            if ($key == 'deleted_at') {
                $fakerFn = 'null';
            }else if (!empty($field['relation'])) {
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
                        if ($key == 'deleted_at') {
                            $fakerFn = 'fake()->boolean()';
                        }else {
                            $fakerFn = 'fake()->boolean()';
                        }
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

                $keyLower = strtolower($key);

                if ($keyLower == 'cpf' || $keyLower == 'cnpj_or_cpf' || $keyLower == 'cnpj' || $keyLower == 'cnpj' || $keyLower == 'document' || Str::contains($keyLower, 'cpf') || Str::contains($keyLower, 'cnpj')) {
                    $fakerFn = 'fake()->numerify("###.###.###-##")';
                }

                if (Str::contains($keyLower, 'phone') || Str::contains($keyLower, 'celular') || Str::contains($keyLower, 'telefone')) {
                    $fakerFn = 'fake()->numerify("(##) #####-####")';
                }

                if ($keyLower == 'rg') {
                    $fakerFn = 'fake()->numerify("##.###.###-#")';
                }

                if (Str::contains($keyLower, 'cep') || Str::contains($keyLower, 'postal_code') || Str::contains($keyLower, 'zip_postal')) {
                    $fakerFn = 'fake()->numerify("#####-###")';
                }

                if ($key == 'email' || Str::contains($keyLower, 'email')) {
                    $fakerFn = 'fake()->email()';
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

    public function getFieldsForFakerDocs(TemplateData &$templateData)
    {
        $fields = [];
        foreach ($templateData->fieldsRaw as $key => $field) {
            if (isset($field['dataForDocs'])) {
                $numberTypes = [
                    'id',
                    'integer',
                    'bigint',
                    'bigInteger',
                    'increments',
                    'integerIncrements',
                    'tinyIncrements',
                    'smallIncrements',
                    'mediumIncrements',
                    'bigIncrements',
                    'tinyInteger',
                    'smallInteger',
                    'mediumInteger',
                    'unsignedInteger',
                    'unsignedTinyInteger',
                    'unsignedSmallInteger',
                    'unsignedMediumInteger',
                    'unsignedBigInteger',
                    'foreignId',
                    'foreignIdFor',
                    'float',
                    'double',
                    'decimal',
                    'unsignedFloat',
                    'unsignedDouble',
                    'unsignedDecimal',
                ];

                if ($field['dataForDocs'] == 'null') {
                    $fields[] = [
                        'name' => $key,
                        'value' => 'null',
                    ];
                }else {
                    if (in_array($field['dbType'], $numberTypes) || $field['dbType'] == 'boolean') {
                        if ($field['dataForDocs'] != '') {
                            $fields[] = [
                                'name' => $key,
                                'value' => $field['dataForDocs'],
                            ];
                        }
                    } else {
                        if ($field['dataForDocs'] == '') {
                            $fields[] = [
                                'name' => $key,
                                'value' => "''",
                            ];
                        }else {
                            $fields[] = [
                                'name' => $key,
                                'value' => "'" . $field['dataForDocs'] . "'",
                            ];
                        }
                    }
                }

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
                        $relations .= "    public function $functionName()  {";
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

                // Essa relação é usada para o usuário apenas quando deleta, não há sentido
                // testar em outros lugares sem ser ao deletar
                if ($key == 'deleted_by') continue;

                // Não é necessário testar se não houver DTO, pois não se sabe o valor que ele vai ganhar
//                if (empty($field['dto'])) continue;

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
                    /**
                     * Caso o valor seja pego pelo usuário, deve verificar pelo usuário, não pela relação
                     */
                    'valueOnUser' => $field['valueOnCreate']['getByUser'] ?? null,
                    'alreadyBeenDefined' => collect($relations)->some(fn($item) => $item['resourceName'] == $tableForeign),
                    'namespace' => $namespaceModel . '\\' . $tableForeign,
                    'resourceName' => $tableForeign,
                    'localKey' => $key,
                    'variable' => $singularRelation,
                    'key' => $primaryKeyName,
                    'dto' => !empty($field['dto']),
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
                case 'time':
                    $cast = "date";
                    break;
                case 'timestamp':
                    $cast = "datetime";
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
                if ($field['default'] === '' && in_array($field['dbType'], ['string', 'text', 'tinyText', 'mediumText', 'longText'])) {
                    $migrationField .= '->default("")';
                }else if (is_bool($field['default'])) {
                    $migrationField .= '->default(' . ($field['default'] ? 'true' : 'false') . ')';
                } else if (is_numeric($field['default'])) {
                    $migrationField .= '->default(' . $field['default'] . ')';
                } else {
                    if ($field['default'] == '') {
                        if ($field['dbType'] == 'string' || $field['dbType'] == 'text' || $field['dbType'] == 'tinyText' || $field['dbType'] == 'mediumText' || $field['dbType'] == 'longText') {
                            $migrationField .= '->default(\'' . $field['default'] . '\')';
                        }
                    } else {
                        $migrationField .= '->default(\'' . $field['default'] . '\')';
                    }
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

                if (empty($field['relation']['hash'])) {
                    $foreignKeys[] = "\$table->foreign('".$key."')->references('".$foreignField."')->on('".$foreignTable."');";
                } else {
                    // unique hash
                    $reduce_name = collect(explode('_', $key))->map(fn($item) => $item[0])->implode('');
                    $hash = $reduce_name . '_' . uniqid();
                    $foreignKeys[] = "\$table->foreign('".$key."', '".$hash."')->references('".$foreignField."')->on('".$foreignTable."');";
                }

            }
        }

        return $foreignKeys;
    }
}