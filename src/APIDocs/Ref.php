<?php

namespace Devesharp\APIDocs;

class Ref
{
    public $name = 'John';

    public $data = [];

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
}
