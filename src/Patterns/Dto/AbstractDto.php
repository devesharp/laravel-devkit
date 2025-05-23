<?php

namespace Devesharp\Patterns\Dto;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isNull;

abstract class AbstractDto extends Collection
{
    use DtoAPIGenerator;

    protected bool $additionalProperties = false;

    protected Collection $data;

    protected Collection $hiddenData;

    protected Collection $originalData;

    protected array $extendsRules = [];

    protected bool $removeRequiredRules = false;

    /**
     * AbstractRequestDto constructor.
     * @param array $data
     */
    public function __construct(array $data, $throw = true)
    {
        $this->originalData = new Collection($data);

        $dataValidate = $data;
        // Remove valores null
        if (!config('devesharp_dev_kit.no_ignore_null_values_dto')) {
            $dataValidate = Helpers::arrayFilterNull($data);
        }

        $rules = $this->getValidateRules();

        $validator = ValidatorLaravel::make($dataValidate, $this->removeHiddenValidation($rules));

        if ($throw) {
            if ($validator->fails()) {
                throw new \Devesharp\Exceptions\Exception(
                    "Error on validate data:\n" . implode("\n", $validator->errors()->all()), \Devesharp\Exceptions\Exception::DATA_ERROR,
                    null,
                    $validator->errors()->getMessages());
            }
        }

        /*
         * Verifica se deve excluir dados extras
         */
        if ($this->additionalProperties) {
            $dataValidate = $dataValidate;
        } else {
            $dataValidate = Helpers::arrayOnly(
                (array) $dataValidate,
                $this->getValidateValues($rules),
            );
        }

        $data = [];
        $dataHidden = [];

        foreach ($dataValidate as $key => $item) {
            if (!empty($rules[$key])) {
                if (!$this->isHidden($rules[$key])) {
                    $data[$key] = $item;
                } else {
                    $dataHidden[$key] = $item;
                }
            } else {
                $data[$key] = $item;
            }
        }

        parent::__construct($dataValidate);

        $this->data = new Collection($data);

        $this->hiddenData = new Collection($dataHidden);

    }

    /**
     * @param bool $raw não remover description das rules
     * @return array
     */
    function getValidateRules($raw = false): array {

        // Current rules
        $rules = $this->configureValidatorRules();

        foreach ($rules as $key => $rule) {

            if(empty($rule)) {
                unset($rules[$key]);
                continue;
            }

            $validations = is_string($rule->rules) ? explode('|', $rule->rules) : $rule->rules;

            // Resgata outra dto e coloca dentro da key escolhida
            foreach ($validations as $item) {
                if (is_string($item) && class_exists($item)) {
                    $otherRules = (new $item([], false))->getValidateRules(true);

                    $newValidation = Collection::make($validations)->filter(function ($rule) use ($item) {
                        return $rule != $item;
                    })->toArray();

                    $rules[$key] = new Rule([...$newValidation, 'array'], $rule->description);
                    foreach ($otherRules as $keyOther => $otherRule) {
                        $rules[$key .'.' . $keyOther] = new Rule($otherRule->rules, $otherRule->description);
                    }

                    break;
                }
            }
        }

        // Remove descriptions for keys
        if (!$raw) {
//            var_dump($rules);
            $rules = Collection::make($rules)->map(function ($rule, $key) {
                if (is_null($rule)) return null;

                if (!($rule instanceof Rule)) {
                    throw new Exception($key . ' not is instance of Rule');
                }

                return $rule->rules;
            })->toArray();
        }

        // Extends Rules
        $rulesExtends = [];

        if (! empty($this->extendsRules)) {
            foreach ($this->extendsRules as $rule) {

                /**
                 * without error handling, because this class will always give error in validation
                 * because the data will always be empty
                 */
                try {
                    if ($this->removeRequiredRules) {
                        $rulesExtends[] = $this->removeRequiredRules((new $rule([], false))->getValidateRules($raw));
                    }else {
                        $rulesExtends[] = (new $rule([], false))->getValidateRules();
                    }
                }catch (\Exception $e) {
                    var_dump($e->getLine());
                    var_dump($e->getMessage());
                }
            }
            unset($rules['_extends_']);
        }


        foreach ($rulesExtends as $rulesExtend) {
            foreach ($rules as $key => $value) {
                if (null === $value) {
                    unset($rulesExtend[$key]);
                    unset($rules[$key]);

                    /*
                     * Procura valores de array
                     * se permissions == null
                     * toda sua array deve ser removida
                     * permissions.bb
                     */
                    foreach ($rulesExtend as $key2 => $value2) {
                        if (0 === strpos($key2, $key . '.')) {
                            unset($rulesExtend[$key2]);
                        }
                    }

                    foreach ($rules as $key2 => $value2) {
                        if (0 === strpos($key2, $key . '.')) {
                            unset($rulesExtend[$key2]);
                        }
                    }
                }
            }

            $rules = array_merge($rulesExtend, $rules);
        }

        return $rules;
    }

    protected function extendRules(string $extendClass)
    {
        $this->extendsRules[] = $extendClass;
    }

    function disableRequiredValues()
    {
        $this->removeRequiredRules = true;
    }

    protected function removeRequiredRules(array $array)
    {
        $newArray = [];

        /**
         * @var string $key
         * @var Rule $value
         */
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $value = str_replace('required|', '', $value);
                $value = str_replace('|required|', '|', $value);
                $re = '/\|required$/m';
                $value = preg_replace($re, "", $value);
                $newArray[$key] = $value;
            } else {
                if ($value instanceof Rule) {
                    if (is_string($value->rules)) {
                        $value->rules = str_replace('required|', '', $value->rules);
                        $value->rules = str_replace('|required|', '|', $value->rules);
                        $re = '/\|required$/m';
                        $value->rules = preg_replace($re, "", $value->rules);
                        $newArray[$key] = $value;
                    } else {
                        $rule = $value->rules;

                        if (array_search('required', $rule) !== false) {
                            unset($rule[array_search('required', $rule)]);
                        }
                        $newArray[$key] = array_values($rule);
                    }
                } else {
                    if (is_string($value)) {
                        $value = str_replace('required|', '', $value);
                        $value = str_replace('|required|', '|', $value);
                        $re = '/\|required$/m';
                        $value = preg_replace($re, "", $value);
                        $newArray[$key] = $value;
                    } else {
                        $rule = $value;

                        if (array_search('required', $rule) !== false) {
                            unset($rule[array_search('required', $rule)]);
                        }
                        $newArray[$key] = array_values($rule);
                    }
                }

            }
        }

        return $newArray;
    }

    protected function isHidden(string|array $value): bool
    {
        if (is_array($value)) {
            return in_array('hidden', $value);
        }

        return Str::contains($value, ['hidden|', '|hidden']);
    }

    protected function removeHiddenValidation(array $array)
    {
        $newArray = [];

        /**
         * @var string $key
         * @var string|array $value
         */
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $rule = $value;
                if (array_search('hidden', $rule) !== false) {
                    unset($rule[array_search('hidden', $rule)]);
                }
                $newArray[$key] = array_values($rule);
            } else {
                $newArray[$key] = str_replace('hidden|', '', $value);
                $newArray[$key] = str_replace('|hidden', '', $newArray[$key]);
            }
        }

        return $newArray;
    }

    protected function getValidateValues(array $rules)
    {
        return array_keys($rules);
    }

    public function toArray() {
        return $this->data->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Sobrescrita de métodos
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
        return parent::__set($key, $value);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
        parent::__unset($name);
    }

    public function offsetUnset($name): void
    {
        unset($this->data[$name]);
        parent::offsetUnset($name);
    }

    public function offsetSet($key, $value): void
    {
        $this->data[$key] = $value;
        parent::offsetSet($key, $value);
    }

    /* @return array */
    abstract protected function configureValidatorRules(): array;
}