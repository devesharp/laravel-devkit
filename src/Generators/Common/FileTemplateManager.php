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
                    $rules = ['alpha_num'];
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
                    if (Str::contains($field['dbType'], 'foreignId')) {
                        $fakerFn = 1;
                    } else {
                        $fakerFn = 'fake()->text(100)';
                    }
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
}
