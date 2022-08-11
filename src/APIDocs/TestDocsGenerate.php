<?php

namespace Devesharp\APIDocs;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\spec\Contact;
use cebe\openapi\spec\License;
use Devesharp\APIDocs\Utils\Get;
use Devesharp\APIDocs\Utils\Route;
use Devesharp\Patterns\Validator\Validator;
use Devesharp\Patterns\Validator\ValidatorAPIGenerator;
use Devesharp\Support\Helpers;
use Illuminate\Console\Command;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\File;
use Illuminate\Support\Arr;

class TestDocsGenerate
{
    protected \Devesharp\APIDocs\Generator $apiDocs;
    protected Route $route;

    protected array $headers = [];
    protected array $query = [];
    protected array $pathParams = [];

    protected array $body = [];

    protected $testCase;

    public function __construct($load, $method, $url, $testCase)
    {
        $apiDocs = \Devesharp\APIDocs\Generator::getInstance();
        $this->route = new Route();
        $this->route->method = $method;
        $this->route->path = $url;
        $this->testCase = $testCase;
    }

//    public function call($method, $url): self
//    {
//        $this->route->method = $method;
//        $this->route->path = $url;
//
//        return $this;
//    }

    public function addHeader($name, $value, $description, $required = false): self
    {
        $this->route->parameters[] = [
            'name' => $name,
            'in' => 'header',
            'required' => $required,
            'description' => $description,
            'example' => $value,
            'schema' => [
                'type' => 'string',
            ],
        ];

        $this->headers[$name] = $value;

        return $this;
    }

    public function addQuery($name, $value, $description, $required = false): self
    {
        $this->route->parameters[] = [
            'name' => $name,
            'in' => 'query',
            'required' => $required,
            'description' => $description,
            'example' => $value,
            'schema' => [
                'type' => 'string',
            ],
        ];

        $this->query[$name] = $value;

        return $this;
    }

    public function addPath($name, $value, $description, $required = false): self
    {
        $this->route->parameters[] = [
            'name' => $name,
            'in' => 'path',
            'required' => $required,
            'description' => $description,
            'example' => $value,
            'schema' => [
                'type' => 'string',
            ],
        ];

        $this->pathParams[$name] = $value;

        return $this;
    }


    /**
     * @param $name
     * @return $this
     */
    function addRouteName($name): self {
        $this->route->summary = $name;
        return $this;
    }

    function addGroups($tags): self {
        $this->route->tags = (array) $tags;
        return $this;
    }

    /**
     * @param array $data
     * @param Validator $validator
     * @param string $validatorClass
     * @param bool $all Mostrar apenas valores de $data ou todos os valores permitidos no validator
     * @return $this
     */
    public function addBody(array $data, string $validatorString = '', string $validatorClass = '', bool $all = false): self
    {
        /** @var ValidatorAPIGenerator $validator */
        if ($validatorString && $validatorClass) {
            $validator = app($validatorString);
            $data = $validator->convertValidatorToData($validatorClass, $data, $all);
        }

        $this->route->body = $data;

        return $this;
    }

    function run() {
        $path = $this->route->path;

        $queries = [];
        $headers = [];
        foreach ($this->route->parameters as $parameter) {
            if ($parameter['in'] == 'path') {
                $path = str_replace(':' . $parameter['name'], $parameter['example'], $path);
            }else if ($parameter['in'] == 'query') {
                $queries[] = $parameter['name'] . '=' . $parameter['example'];
            }else if ($parameter['in'] == 'header') {
                $headers[$parameter['name']] = $parameter['example'];
            }
        }

        if (!empty($queries)) {
            $path = $path . '?' . implode('&', $queries);
        }

        if (!empty($this->route->body)) {
            $request = $this->testCase->{$this->route->method}($path, $this->route->body, $headers);
        } else {
            $request = $this->testCase->{$this->route->method}($path, $headers);
        }

        $this->route->statusCode = $request->getStatusCode();
        $this->route->response = $request->json();

        return $request;
    }

}
