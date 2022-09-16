<?php

namespace Devesharp\SwaggerGenerator;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\spec\Contact;
use cebe\openapi\spec\License;
use Devesharp\SwaggerGenerator\Utils\Route;
use Devesharp\Patterns\Dto\AbstractDto;
use Devesharp\Patterns\Validator\Validator;
use Devesharp\Patterns\Validator\ValidatorAPIGenerator;
use Devesharp\Support\Helpers;
use Illuminate\Console\Command;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TestDocsGenerate
{
    protected \Devesharp\SwaggerGenerator\Generator $apiDocs;
    protected Route $route;

    protected array $headers = [];
    protected array $query = [];
    protected array $pathParams = [];

    protected array $body = [];

    protected $testCase;

    public function __construct($load, $method, $url, $testCase)
    {
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

    public function addPath($name, $value, $description): self
    {
        $this->route->parameters[] = [
            'name' => $name,
            'in' => 'path',
            'required' => true,
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
    function addRouteName($name, $description = ''): self {
        $this->route->title = $name;
        $this->route->description = $description;
        return $this;
    }

    function addVaritionName($name, $description = ''): self {
        $this->route->variationName = $name;
        $this->route->variationDescription = $description;
        return $this;
    }

    /**
     * @param $name
     * @param string $routeDoc
     * @return $this
     */
    function setRouteInfo($name, string $routeDoc = ''): self {
        if (class_exists($routeDoc)) {
            $routeInfo = (new $routeDoc())->getRouteInfo($name);
            $this->addRouteName($routeInfo->name, $routeInfo->description);
        }

        return $this;
    }

    function addGroups($tags): self {
        $this->route->tags = (array) $tags;
        return $this;
    }

    /**
     * @param array $data
     * @param Validator $validator
     * @param string $validatorName
     * @param bool $all Mostrar apenas valores de $data ou todos os valores permitidos no validator
     * @return $this
     */
    public function addBody($data, string $DtoClass = null): self
    {
        /** @var ValidatorAPIGenerator $validator */
        if (!empty($DtoClass)) {
            $data = new $DtoClass($data);
            $this->route->bodyRequired = $data->getRequireds();
            $this->route->bodyDescription = $data->getDescriptions();
            $this->route->body = $data->toArray();
            $this->route->bodyComplete = $data->getDataModel(true);
        }else {
            $this->route->body = $data;
            $this->route->bodyComplete = $data;
        }

        return $this;
    }

    public function treatmentData($data) {
        $dot = Arr::dot($data);
        $newData = [];

        foreach ($dot as $key => $value) {
            if ($value instanceof Ref) {
                Arr::set($newData, $key, $value->getValue());
            } else {
                Arr::set($newData, $key, $value);
            }
        }

        return $newData;
    }

    function run() {
        $path = $this->route->path;
        $pathFixed = $this->route->path;

        $queries = [];
        $headers = [];

        /**
         * Resgatar path, query e header para a rota
         */
        foreach ($this->route->parameters as $parameter) {
            if ($parameter['in'] == 'path') {
                $path = str_replace(':' . $parameter['name'], $parameter['example'], $path);
                $pathFixed = str_replace(':' . $parameter['name'], '{' . $parameter['name'] . '}', $pathFixed);
            }else if ($parameter['in'] == 'query') {
                $queries[] = $parameter['name'] . '=' . $parameter['example'];
            }else if ($parameter['in'] == 'header') {
                $headers[$parameter['name']] = $parameter['example'];
            }
        }

        /**
         * Converter array de queries em string
         */
        if (!empty($queries)) {
            $path = $path . '?' . implode('&', $queries);
        }

        /**
         * Chamar metodo da rota
         */
        switch ($this->route->method) {
            case 'post':
                $request = $this->testCase->post($path, $this->treatmentData($this->route->body), $headers);
                break;
            case 'put':
                $request = $this->testCase->put($path, $this->treatmentData($this->route->body), $headers);
                break;
            case 'delete':
                $request = $this->testCase->delete($path, $this->treatmentData($this->route->body), $headers);
                break;
            case 'patch':
                $request = $this->testCase->patch($path, $this->treatmentData($this->route->body), $headers);
                break;
            default:
                $request = $this->testCase->get($path, $headers);
                break;
        }

        $this->route->path = $pathFixed;
        $this->route->statusCode = $request->getStatusCode();
        $this->route->response = $request->json();

//        if (multipart/form-data)

        $apiDocs = \Devesharp\SwaggerGenerator\Generator::getInstance();
        $apiDocs->addRoute($this->route);

        return $request;
    }

}
