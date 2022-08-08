<?php

namespace Devesharp\APIDocs;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\spec\Contact;
use cebe\openapi\spec\License;
use Devesharp\APIDocs\Utils\Get;
use Devesharp\APIDocs\Utils\Route;
use Devesharp\Support\Helpers;
use Illuminate\Console\Command;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;
use Illuminate\Http\File;
use Illuminate\Support\Arr;

class Generator
{
    private static $instance;

    /**
     * @var OpenApi The OpenAPI
     */
    private OpenApi $openAPIJSON;

    /**
     * @var string The title of the API.
     */
    public $title = 'API Docs';

    /**
     * @var string The description of the API.
     */
    public $description = 'API Docs';

    /**
     * @var array The servers of the API.
     */
    private $servers = [];

    private string $file = 'openapi-docs.yml';

    public function __construct()
    {
        // create base API Description
        $this->openAPIJSON = new OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([]),
            "servers" => [],
            "paths" => [],
        ]);
    }

    /**
     * Create new instance or load existing file.
     *
     * @param $file
     * @return void
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    function load($file) {
        if (file_exists($file)) {
            $this->openAPIJSON = \cebe\openapi\Reader::readFromYamlFile($file);
        }
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        if(!empty($this->openAPIJSON))
            $this->openAPIJSON->info->title = $title;
    }

    public function setVersion(string $version): void
    {
        $this->openAPIJSON->info->version = $version;
    }

    public function addBasicAuth(string $name): void
    {
        if (!isset($this->openAPIJSON->components->securitySchemes)) {
            $this->openAPIJSON->components = new \cebe\openapi\spec\Components([
                'securitySchemes' => [
                    $name => new \cebe\openapi\spec\SecurityScheme([
                        'type' => 'http',
                        'scheme' => 'basic',
                        'description' => 'Bearer Authentication',
                    ]),
                ],
            ]);
        } else {
            $securitySchemes = $this->openAPIJSON->components->securitySchemes;
            $securitySchemes[$name] = new \cebe\openapi\spec\SecurityScheme([
                'type' => 'http',
                'scheme' => 'basic',
                'description' => 'Bearer Authentication',
            ]);
            $this->openAPIJSON->components->securitySchemes = $securitySchemes;
        }
    }

    public function addBearerAuth(string $name): void
    {
        if (!isset($this->openAPIJSON->components->securitySchemes)) {
            $this->openAPIJSON->components = new \cebe\openapi\spec\Components([
                'securitySchemes' => [
                    $name => new \cebe\openapi\spec\SecurityScheme([
                        'type' => 'http',
                        'scheme' => 'basic',
                        'bearerFormat' => 'Bearer',
                        'description' => 'Bearer Authentication',
                    ]),
                ],
            ]);
        } else {
            $securitySchemes = $this->openAPIJSON->components->securitySchemes;
            $securitySchemes[$name] = new \cebe\openapi\spec\SecurityScheme([
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'Bearer',
                'description' => 'Bearer Authentication',
            ]);
            $this->openAPIJSON->components->securitySchemes = $securitySchemes;
        }

    }

    public function setTermsOfService(string $termsOfService): void
    {
        $this->openAPIJSON->info->termsOfService = $termsOfService;
    }

    public function setContact(string $name, string $url = '', string $email = ''): void
    {
        $this->openAPIJSON->info->contact = new Contact([
            'name' => $name,
            'url' => $url,
            'email' => $email,
        ]);
    }

    public function setLicense(string $name, string $url): void
    {
        $this->openAPIJSON->info->license = new License([
            'name' => $name,
            'url' => $url,
        ]);
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        if(!empty($this->openAPIJSON))
            $this->openAPIJSON->info->description = $description;
    }

    /**
     * Add a server to the API.
     *
     * @param $url
     * @param $description
     * @return void
     */
    public function addServers($url, $description = ''): void
    {
        if ($this->openAPIJSON->servers[0]->url == '/') {
            $servers = [];
        } else {
            $servers = $this->openAPIJSON->servers;
        }

        $servers[] = [
            'url' => $url,
            'description' => $description,
        ];

        $this->openAPIJSON->servers = $servers;
    }

    function addRoute(Route $route) {

        if (!isset($this->openAPIJSON->paths[$route->path])) {
            $this->openAPIJSON->paths[$route->path] = new PathItem([]);
        }

        $path = &$this->openAPIJSON->paths[$route->path];
        $method = mb_strtolower($route->method);

        $pathExist = false;

        $path->{$method} = new \cebe\openapi\spec\Operation([
            'tags' => $route->tags ?? [],
            'summary' => $route->summary ?? '',
            'description' => $route->description ?? '',
            'externalDocs' => $route->externalDocs ?? [],
            'parameters' => $route->parameters ?? [],
            'deprecated' => $route->deprecated ?? false,
            'security' => $route->security ?? [],
            'responses' => new \cebe\openapi\spec\Responses([])
        ]);

        $path->{$method}->responses = [
            $route->statusCode => [
                'description' => $route->descriptionResponse,
                'content' => [
                    $route->bodyType => [
                        'schema' => $this->dataToSchema($route->response, true)
                    ]
                ]
            ]
        ];

        if (!empty($route->body)) {
            $path->{$method}->requestBody = [
                'description' => $route->descriptionResponse,
                'content' => [
                    $route->bodyType => [
                        'schema' => $this->dataToSchema($route->body, true, $route->bodyRequired)
                    ]
                ]
            ];
        }
    }

    function dataToSchema($data, $addExample = false, $required = []) {

        $schema = [];
        if (is_object($data) || (is_array($data) && Helpers::isArrayAssoc($data))) {

            if (is_object($data)) {
                if (get_class($data) == \Illuminate\Http\Testing\File::class || get_class($data) == File::class) {
                    return [
                        'type' => 'string',
                        'format' => 'binary',
                    ];
                }
            }

            $schema['type'] = 'object';
            $schema['properties'] = [];

            foreach ($data as $key => $datum) {
                $schema['properties'][$key] = $this->dataToSchema($datum, $addExample);
            }
        }else if (is_array($data)) {
            $schema['type'] = 'array';
            $schema['items'] = [];

            foreach ($data as $item) {
                $item = $this->dataToSchema($item, $addExample);
                $schema['items'][$item['type']] = $item;
            }

            $items = array_values($schema['items']);

            if (count($items) > 1) {
                $schema['items'] = [
                    'anyOf' => $items
                ];
            } else if (count($items) == 1) {
                $schema['items'] = $items[0];
            } else {
                $schema['items'] = [
                    'type' => 'string'
                ];
            }
        } else {
            if (gettype($data) == 'integer') {
                $schema['type'] = gettype($data);
                $schema['format'] = 'int64';
            }else if (gettype($data) == 'double') {
                $schema['type'] = 'number';
                $schema['format'] = 'double';
            } else {
                $schema['type'] = gettype($data);
            }

            if ($addExample) {
                $schema['example'] = $data;
            }
        }

        if (!empty($required)) {
            return $this->addRequiredToSchema($schema, $required);
        }

        return $schema;
    }

    function addRequiredToSchema($data, $required = []) {
        $data2 = new \Adbar\Dot($data);

        $keys = [];

        foreach ($required as $item) {
            $explode = explode('.', $item);
            $string = [];

            foreach ($explode as $key => $item2) {
                if (is_numeric($item2) || $item2 == '*') {
                    $string[] = 'items';
                } else {
                    $string[] = 'properties';

                    $string[] = $item2;
                }
            }

            $keys[] = implode('.', $string);
        }

        foreach ($keys as $item) {
            $key = $data2->get($item);

            $keyRequired = '';
            if (!empty($key)) {
                $explode = explode('.', $item);
                $keyRequired = $explode[count($explode) - 1];
                array_pop($explode);
                array_pop($explode);
                $item = implode('.', $explode);
            }

            $key = $data2->get($item);

            if (!empty($key)) {
                if (!in_array($keyRequired, $key['required'] ?? [])) {
                    $key['required'][] = $keyRequired;
                    $data2->set($item, $key);
                }
            }

            if (empty($item)) {
                $required = $data2->get('required');
                if (!in_array($keyRequired, $required ?? [])) {
                    $required[] = $keyRequired;
                    $data2->set('required', $required);
                }
            }
        }

        return $data2->all();
    }


    public function toYml(): string
    {
        return \cebe\openapi\Writer::writeToYaml($this->openAPIJSON);
    }

    /**
     * Get instance of the class.
     *
     * @param $increment bool If true, will append to the existing file.
     * @return static
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    public static function getInstance(bool $increment = false): self
    {
        if(self::$instance === null){
            self::$instance = new self;
            self::$instance->init($increment);
        }

        return self::$instance;
    }
}
