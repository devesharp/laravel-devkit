<?php

namespace Devesharp\Patterns\Validator;

use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;
use PHPUnit\TextUI\Help;

trait ValidatorAPIGenerator
{

    public function getDataModel(string $validatorName, $dataOriginal = [], $showAll = false) {

        $data = $this->convertValidatorToData($validatorName, $dataOriginal, $showAll);

        var_dump($data);

        return [

        ];
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
    public function convertValidatorToData(string $validatorName, $dataOriginal = [], $showAll = false) {
        if (!isset($this->rules[$validatorName])) {
            return $dataOriginal;
        }

        $data = $this->validate($dataOriginal, $this->getValidateWithoutRequired($validatorName))->toArray();
        $data = \Illuminate\Support\Arr::dot($data);

        $newData = [];

        if ($validatorName === 'search') {
//            \Illuminate\Support\Arr::set($data, 'query.offset', 0);
            $data["query.offset"] =  0;
//            \Illuminate\Support\Arr::set($data, 'query.limit', 20);
            $data["query.limit"] =  20;
//            \Illuminate\Support\Arr::set($data, 'query.sort', '');
            $data["query.sort"] =  '';
        }

        $schema = array_merge_recursive($this->getValidateWithoutRequired($validatorName), $data);

        foreach ($schema as $keySchemaOriginal => $valueSchema) {
            if ($keySchemaOriginal == '_extends') continue;

            $keySchema = preg_replace('/\*/', '[\d]', $keySchemaOriginal);
            $existKey = false;

            foreach ($data as $key => $value) {
                $currentValue = $value;
                if (!!preg_match('/'.$keySchema . '/', $key) || $this->additionalProperties)
                {
                    var_dump('123');
                    $existKey = true;
                    \Illuminate\Support\Arr::set($newData, $key, $currentValue);
                }
            }

            if (!$existKey && $showAll) {
                $key = preg_replace('/\*/', '0', $keySchemaOriginal);
                $value = is_array($valueSchema) ? $valueSchema[0] : $valueSchema;

                if (in_array([
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
                ], explode('|', $value))) {
                    $value = 'string';
                }else if (in_array(['boolean'], explode('|', $value))) {
                    $value = false;
                }else if (in_array(['integer', 'numeric'], explode('|', $value))) {
                    $value = 1;
                }else if (in_array(['nullable'], explode('|', $value))) {
                    $value = null;
                }else if (in_array(['array'], explode('|', $value))) {
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
    public function getRequireds(string $validatorName) {
        $data = [];

        foreach ($this->rules[$validatorName] as $key => $value) {
            $key = str_replace('*', '0', $key);

            if (in_array('required', explode('|', is_array($value) ? $value[0] : $value))) {
                $data[] = $key;
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
    public function getDescriptions(string $validatorName) {

        $rules = $this->getValidate($validatorName);

        $descriptions = [];

        foreach ($rules as $key => $value) {
            if (is_array($value) && count($value) >= 2) {
                $descriptions[$key] = $value[1];
            }
        }

        return $descriptions;
    }
}
