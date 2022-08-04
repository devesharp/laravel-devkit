<?php

namespace Devesharp\CRUD;

use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;

class Validator
{
    /**
     * Rules
     * @var array
     * ['validator-laravel', 'description-docs']
     */
    protected array $rules = [];

    /**
     * Remover dados adicionais
     */
    protected bool $additionalProperties = false;

    /**
     * @param $_data
     * @param $rules
     * @param bool $requiredAll
     * @return Collection
     * @throws Exception
     */
    public function validate($_data, $rules, $requiredAll = false)
    {
        // Remove valores null
        $_data = Helpers::arrayFilterNull($_data);

        if (is_string($rules)) {
            $rules = $this->getValidate($rules);
        }

        $rules = Collection::make($rules)->map(function ($rule) {
            if (is_array($rule)) {
                return $rule[0];
            }
            return $rule;
        })->toArray();

        $validator = ValidatorLaravel::make($_data, $rules);

        if ($validator->fails()) {
            throw new \Devesharp\CRUD\Exception(
                "Error on validate data:\n" . implode("\n", $validator->errors()->all()), \Devesharp\CRUD\Exception::DATA_ERROR,
                null,
                $validator->errors()->getMessages());
        }

        /*
         * Verifica se deve excluir dados extras
         */
        if ($this->additionalProperties) {
            $data = $_data;
        } else {
            $data = Helpers::arrayOnly(
                (array) $_data,
                $this->getValidateValues($rules),
            );
        }

        return new Collection($data);
    }

    /**
     * Recuperar validação do contexto.
     *
     * @param $context
     * @param mixed $requiredAll
     *
     * @return mixed
     */
    protected function getValidate($context, $requiredAll = false)
    {
        if (! empty($this->rules[$context]['_extends'])) {
            $rules = $this->getExtendsValidator(
                $this->rules[$context],
                $this->rules[$context]['_extends'],
            );

            return $requiredAll
                ? $this->setAllValidateRequired($rules)
                : $rules;
        }

        return $requiredAll
            ? $this->setAllValidateRequired($this->rules[$context])
            : $this->rules[$context];
    }

    /**
     * Recuperar validação do contexto.
     * E incrementar valores de busca `query`.
     *
     * @param  $context
     * @param  bool        $requiredAll
     * @return array|mixed
     */
    protected function getValidateWithSearch($context, $requiredAll = false)
    {
        if (! empty($this->rules[$context]['_extends'])) {
            $rules = $this->getExtendsValidator(
                $this->rules[$context],
                $this->rules[$context]['_extends'],
            );

            $rules['query'] = 'array';
            $rules['query.limit'] = 'numeric';
            $rules['query.pagination'] = 'numeric';
            $rules['query.offset'] = 'numeric';

            return $requiredAll
                ? $this->setAllValidateRequired($rules)
                : $rules;
        }

        $rules = $this->rules[$context];
        $rules['query'] = 'array';
        $rules['query.limit'] = 'numeric';
        $rules['query.pagination'] = 'numeric';
        $rules['query.offset'] = 'numeric';

        return $requiredAll
            ? $this->setAllValidateRequired($this->rules[$context])
            : $rules;
    }

    /**
     * @param array  $rules
     * @param string $extends
     *
     * @return array
     */
    public function getExtendsValidator(array $rules, string $extends)
    {
        $extend = [];

        if (! empty($this->rules[$extends]['_extends'])) {
            $extend = $this->getExtendsValidator(
                $this->rules[$extends],
                $this->rules[$extends]['_extends'],
            );
        } else {
            $extend = $this->rules[$extends];
        }

        foreach ($rules as $key => $value) {
            if (null === $value) {
                unset($extend[$key]);
                unset($rules[$key]);

                /*
                 * Procura valores de array
                 * se permissions == null
                 * toda sua array deve ser removida
                 * permissions.bb
                 */
                foreach ($extend as $key2 => $value2) {
                    if (0 === strpos($key2, $key . '.')) {
                        unset($extend[$key2]);
                    }
                }

                foreach ($rules as $key2 => $value2) {
                    if (0 === strpos($key2, $key . '.')) {
                        unset($extend[$key2]);
                    }
                }
            }
        }

        return array_merge($extend, $rules);
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    protected function getValidateValues(array $rules)
    {
        return array_keys($rules);
    }

    public function removeRequiredRules(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            if ('_extends' === $key) {
                continue;
            }

            $newArray[$key] = str_replace('required|', '', $value);
            $newArray[$key] = str_replace('|required', '', $newArray[$key]);
        }

        return $newArray;
    }

    public function setAllValidateRequired(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            if ('_extends' === $key) {
                continue;
            }
            $newArray[$key] = 'required|' . $value;
        }

        return $newArray;
    }

    public function additionalProperties($additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function convertValidatorToData(string $validatorName, $data = []) {
        if (!isset($this->rules[$validatorName])) {
            return $data;
        }

        $data = $this->validate($data, $this->getValidate($validatorName))->toArray();

        if ($validatorName === 'search') {
            \Illuminate\Support\Arr::set($data, 'query.offset', 0);
            \Illuminate\Support\Arr::set($data, 'query.limit', 20);
            \Illuminate\Support\Arr::set($data, 'query.sort', '');
        }

        $rules = $this->rules[$validatorName];

        if (! empty($this->rules[$validatorName]['_extends'])) {
            $rules = $this->getExtendsValidator(
                $this->rules[$validatorName],
                $this->rules[$validatorName]['_extends'],
            );
        }

        foreach ($rules as $key => $value) {
            if ($key == '_extends') continue;

            $key = str_replace('*', '0', $key);
            $currentValue = \Illuminate\Support\Arr::get($data, $key);

            if (in_array('string', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, 'string');
            }else if (in_array('alpha_numeric', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, 'string');
            }else if (in_array('alpha', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, 'string');
            }else if (in_array('numeric', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, 1);
            }else if (in_array('boolean', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, false);
            }else if (in_array('array', explode('|', $value))) {
                if (empty($currentValue))
                    \Illuminate\Support\Arr::set($data, $key, []);
            }
        }

        return $data;
    }

    public function getRequireds(string $validatorName) {
        $data = [];

        foreach ($this->rules[$validatorName] as $key => $value) {
            $key = str_replace('*', '0', $key);
            if (in_array('required', explode('|', $value))) {
                $data[] = $key;
            }
        }

        return $data;
    }

    public function getDescriptions(string $validatorName) {
        $data = [];

        if (!isset($this->descriptions[$validatorName])) {
            return $data;
        }

        $descriptions = $this->descriptions[$validatorName];

        if (! empty($this->rules[$validatorName]['_extends'])) {
            $descriptionsExtends = $this->descriptions[$this->rules[$validatorName]['_extends']];
            $descriptions = array_merge_recursive($descriptionsExtends, $descriptions);
        }

        return $descriptions;
    }
}
