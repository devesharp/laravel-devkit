<?php

namespace Devesharp\Generators\Common;


use Illuminate\Support\Str;

class FileTemplateManager
{
    public array $fileContent = [];

    public function __construct($file)
    {
        $this->fileContent = \yaml_parse(file_get_contents($file));
    }

    public function getFieldsForDto()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {

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

            if (!isset($field['isDto']) || $field['isDto']) {

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

    public function getFieldsForDtoSearch()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {

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

    public function getFieldsForTransformer()
    {
        $fields = [];

        foreach ($this->fileContent['fields'] as $key => $field) {

            $type = "string";
            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                    $type = 'number';
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

            if (!empty($field['inTransformer'])) {
                $fields[] = [
                    'name' => $key,
                    'type' => $type,
                ];
            }
        }
        return $fields;
    }

    public function getFiltersSearchable()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
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

    public function getFiltersSort()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
            if (!empty($field['sort'])) {
                $fields[] = [
                    'name' => $key
                ];
            }
        }
        return $fields;
    }

    public function getUsersServiceRelation()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
            if (!empty($field['getByUser'])) {
                $table = explode(",", $field['relation'])[1];

                $fields[] = [
                    'fieldName' => $key,
                    'userFieldName' => $table == "Users" || $table == "User" ? "id" : $key,
                ];
            }
        }
        return $fields;
    }

    public function getFieldsForFaker()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
            $fakerFn = '""';
            switch (strtolower($field['dbType'])) {
                case 'integer':
                case 'unsignedinteger':
                case 'smallinteger':
                case 'biginteger':
                case 'unsignedbiginteger':
                case 'long':
                    $fakerFn = 'fake()->randomFloat(2)';
                    break;
                case 'double':
                case 'float':
                case 'decimal':
                    $fakerFn = 'fake()->randomNumber()';
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
                default:
                    if (Str::contains($field['dbType'], 'foreign')) {
                        $fakerFn = 1;
                    } else {
                        $fakerFn = 'fake()->text(100)';
                    }
            }

            if ($key == "enabled") {
                $fakerFn = 'true';
            }

            if (empty($field['primary']) && $key !== 'created_at' && $key !== 'updated_at') {
                $fields[] = [
                    'name' => $key,
                    'faker_function' => $fakerFn,
                ];
            }
        }
        return $fields;
    }

    public function getPropertyPHPDocs()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
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
                    $type = "\Illuminate\Support\Carbon";
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

    public function getModelRelationFunctions($namespaceModel)
    {
        $relations = '';

        foreach ($this->fileContent['fields'] as $key => $field) {
            if (!empty($field['relation'])) {
                $typeRelation = explode(",", $field['relation'])[0];

                $tableForeign = explode(",", $field['relation'])[1];
                $singularRelation = Str::camel(Str::singular($tableForeign));
                $pluralRelation = Str::camel(Str::plural($tableForeign));
                $primaryKeyName = explode(",", $field['relation'])[2];

                if (str_replace('_id', '', $key) != $singularRelation && substr($key, -2) != "Id") {
                    $singularRelation .= Str::studly($key);
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

                if (!empty($functionName)) {
                    $relations .= "\n";
                    if ($relation == 'belongsTo') {
                        $relations .= "    public function $functionName(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\\$namespaceModel\\$tableForeign {";
                    }else {
                        $relations .= "    public function $functionName():  {";
                    }
                    $relations .= "\n";
                    $relations .= "        return \$this->$relation(\\$namespaceModel\\$tableForeign::class, '$key', '$primaryKeyName');";
                    $relations .= "\n";
                    $relations .= "    }";
                    $relations .= "\n";
                }
            }

        }

        return $relations;
    }

    public function getFieldsForCasts()
    {
        $fields = [];
        foreach ($this->fileContent['fields'] as $key => $field) {
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
                    'cast' => 'cast',
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

    public function getFieldsForMigration()
    {
        $fields = [];
        $foreignKeys = [];
        $createdAtField = null;
        $updatedAtField = null;
        $enabledAtField = null;

        foreach ($this->fileContent['fields'] as $key => $field) {
            if ($key == 'created_at') {
                $createdAtField = $field;
                continue;
            } else if ($key == 'updated_at') {
                $updatedAtField = $field;
                continue;
            } else if ($key == 'enabled') {
                $enabledAtField = $field;
            }

            if (Str::contains($field['dbType'], 'foreign')) {
                $migrationField = '$table->unsignedBigInteger(\'' . $key . '\')';
            } else {
                if ($field['dbType'] == "id" && $key == "id") {
                    $migrationField = '$table->' . $field['dbType'] . '()';
                } else {
                    $migrationField = '$table->' . $field['dbType'] . '(\'' . $key . '\')';
                }
            }

            if (!empty($field['default'])) {
                $migrationField .= '->default(\'' . $field['default'] . '\')';
            }

            if ($field['dbType'] != "id" && !empty($field['primary'])) {
                $migrationField .= '->primary()';
            }

            if (!empty($field['isNullable'])) {
                $migrationField .= '->nullable()';
            }

            $migrationField .= ';';

            $fields[] = $migrationField;

            if (Str::contains($field['dbType'], 'foreign')) {
                $foreignTable = Str::snake(trim(explode(",", $field['relation'])[1]));
                $foreignField = explode(",", $field['relation'])[2] ?? 'id';

                $foreignKeys[] = "\$table->foreign('".$key."')->references('".$foreignField."')->on('".$foreignTable."');";
            }
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

        return [
            'fields' => $fields,
            'foreignKeys' => $foreignKeys,
        ];
    }
}
