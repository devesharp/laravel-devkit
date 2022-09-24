<?php

namespace Devesharp\SwaggerGenerator\Utils;


use Devesharp\SwaggerGenerator\Generator;

abstract class Ref
{
    public $name = 'John';

    public $data = [];

    public $value;

    public function __construct($value)
    {
        $this->value = $value;

        $this->configure();
    }

    function setEnum(string $name, array $array, $description) {
        $this->data = [
            'type' => 'string',
            'enum' => $array,
            'description' => $description,
        ];
    }

    function setData(array $array, $required = [], $description = []) {
        $apiDocs = new Generator();
        $this->data = $apiDocs->dataToSchema($array, true, $required, $description);
    }

    function getData() {
        return $this->data;
    }

    public function getValue() {
        return $this->value;
    }

    abstract protected function configure(): void;
}
