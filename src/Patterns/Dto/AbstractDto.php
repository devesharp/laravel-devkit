<?php

namespace Devesharp\Patterns\Dto;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Facades\Validator as ValidatorLaravel;
use Illuminate\Support\Str;

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

        // Remove valores null
        $dataValidate = Helpers::arrayFilterNull($data);

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

        // Remove descriptions for keys
        if (!$raw) {
            $rules = Collection::make($rules)->map(function ($rule) {
                if (is_array($rule)) {
                    return $rule[0];
                }
                return $rule;
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

                }catch (\Exception $e) {}
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

        foreach ($array as $key => $value) {
            $newArray[$key] = str_replace('required|', '', $value);
            $newArray[$key] = str_replace('|required', '', $newArray[$key]);
        }

        return $newArray;
    }

    protected function isHidden(string $value): bool
    {
        return Str::contains($value, ['hidden|', '|hidden']);
    }

    protected function removeHiddenValidation(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            $newArray[$key] = str_replace('hidden|', '', $value);
            $newArray[$key] = str_replace('|hidden', '', $newArray[$key]);
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

    /* @return array */
    abstract protected function configureValidatorRules(): array;
}