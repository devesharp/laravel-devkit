<?php

namespace Devesharp\Patterns\Dto;

use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;
use Illuminate\Validation\Rules\Enum;
use PHPUnit\TextUI\Help;
use Tests\Units\Dto\Mocks\Category;

trait DtoAPIGenerator
{
    public function getDataModel(string $validatorName, $dataOriginal = [], $showAll = false) {

        $data = $this->convertValidatorToData($validatorName, $dataOriginal, $showAll);

        return $data;
    }

    public function convertArrayToModel($data) {
        $newData = [];

        foreach ($data as $key => $value) {

            if (is_bool($value)) {
                $newData[] = [
                    'type' => 'boolean'
                ];
            } else {

            }

        }

        return $newData;
    }

    /**
     * Converte schema para array com valores padrão
     * Pode ser enviado $data para definir valores default nos campos que desejar
     * @param string $validatorName
     * @param $data
     * @return array|mixed
     */
    public function convertValidatorToData($showAll = false) {

        $data = \Illuminate\Support\Arr::dot($this->all());

        $newData = [];

        $schema = array_merge_recursive($this->getValidateRules(), $data);

        /**
         * @var string $keySchemaOriginal
         * @var Rule $valueSchema
         */
        foreach ($schema as $keySchemaOriginal => $valueSchema) {
            if ($keySchemaOriginal == '_extends') continue;

            $keySchema = preg_replace('/\*/', '[\d]', $keySchemaOriginal);
            $existKey = false;

            foreach ($data as $key => $value) {
                $currentValue = $value;
                if (!!preg_match('/'.$keySchema . '/', $key) || $this->additionalProperties)
                {
                    $existKey = true;
                    \Illuminate\Support\Arr::set($newData, $key, $currentValue);
                }
            }

            if (!$existKey && $showAll) {
                $key = preg_replace('/\*/', '0', $keySchemaOriginal);
                $value = '';
                $rules = is_array($valueSchema) ? $valueSchema : explode('|', $valueSchema);

                if (Helpers::inArrayAny([
                    'alpha',
                    'alpha_dash',
                    'alpha_num',
                    'alpha_numeric',
                    'current_password',
                    'date',
                    'declined',
                    'ends_with',
                    'in',
                    'email',
                    'enum',
                    'ip',
                    'ipv4',
                    'ipv6',
                    'json',
                    'in_array',
                    'mac_address',
                    'password',
                    'regex',
                    'starts_with',
                    'string',
                    'timezone',
                    'url',
                    'iuud'
                ], $rules)) {
                    $value = 'string';
                }else if (Helpers::inArrayAny(['boolean'], $rules)) {
                    $value = false;
                }else if (Helpers::inArrayAny(['integer', 'numeric', 'numeric_string'], $rules)) {
                    $value = 1;
                }else if (Helpers::inArrayAny(['nullable'], $rules)) {
                    $value = null;
                }else if (Helpers::inArrayAny(['array'], $rules)) {
                    $value = [];
                } else {
                    $value = 'string';
                }

                \Illuminate\Support\Arr::set($newData, $key, $value);
            }
        }

        return $newData;
    }

    /**
     * Retorna data type
     *
     * https://swagger.io/docs/specification/data-models/data-types/#file
     *
     * @param $mixed
     * @return string
     */
    protected function getTypeDataTypes($mixed): string {
        if (is_numeric($mixed)) {
            return 'numeric';
        }else if (is_string($mixed)) {
            return 'string';
        }else if (is_array($mixed) && Helpers::isArrayAssoc($mixed)) {
            return 'array';
        }else if (is_object($mixed) || (is_array($mixed) && !Helpers::isArrayAssoc($mixed))) {
            return 'object';
        }else if (is_bool($mixed)) {
            return 'boolean';
        } else {
            return 'string';
        }
    }

    /**
     * Resgatar valores obrigatórios das validações
     * Usada no Devesharp API Generator
     * @param string $validatorName
     * @return array|mixed
     */
    public function getRequireds() {
        $data = [];

        /**
         * @var string $key
         * @var string|array $rule
         */
        foreach ($this->getValidateRules() as $key => $rule) {
            $key = str_replace('*', '0', $key);
            if (is_array($rule)) {
                if (Arr::exists($rule, 'required')) {
                    $data[] = $key;
                }
            } else {
                if (in_array('required', explode('|', $rule))) {
                    $data[] = $key;
                }
            }
        }

        return $data;
    }

    /**
     * Resgatar descrições das validações
     * Usada no Devesharp API Generator
     * @param string $validatorName
     * @return array|mixed
     */
    public function getDescriptions() {

        $rules = $this->getValidateRules(true);

        $descriptions = [];

        /**
         * @var string $key
         * @var Rule $rule
         */
        foreach ($rules as $key => $rule) {
            if (!empty($rule->description)) {
                $descriptions[$key] = $rule->description;
            }
        }

        return $descriptions;
    }

    /**
     * Resgatar enum das validações
     * Usada no Devesharp API Generator
     * @param string $validatorName
     * @return array|mixed
     */
    public function getEnums() {

        $rules = $this->getValidateRules(true);

        $enum = [];

        /**
         * @var string $key
         * @var Rule $rule
         */
        foreach ($rules as $key => $rule) {
            if (is_array($rule->rules)) {
                foreach ($rule->rules as $ruleValue) {
                    if($ruleValue instanceof Enum) {
                        // Hack para resgatar valor private de Illuminate\Validation\Rules\Enum
                        $reflectionClass = new \ReflectionClass($ruleValue);
                        $reflectionProperty = $reflectionClass->getProperty('type');
                        $reflectionProperty->setAccessible(true);
                        $enumClass = $reflectionProperty->getValue($ruleValue);
                        $items = [];
                        foreach ($enumClass::cases() as $case) {
                            $items[] = $case->value;
                        }
                        $enum[$key] = $items;
                    }
                }
            }
        }

        return $enum;
    }
}
