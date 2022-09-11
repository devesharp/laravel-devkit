<?php

namespace Devesharp\Generators\Common;


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
}
