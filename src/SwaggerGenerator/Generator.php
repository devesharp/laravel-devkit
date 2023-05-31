<?php

/**
 * Classe responsável por gerar a documentação da Swagger da API
 */
namespace Devesharp\SwaggerGenerator;

use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\spec\Contact;
use cebe\openapi\spec\License;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\PathItem;
use Devesharp\Support\Helpers;
use Devesharp\SwaggerGenerator\Utils\Get;
use Devesharp\SwaggerGenerator\Utils\Ref;
use Devesharp\SwaggerGenerator\Utils\Route;
use Illuminate\Http\File;
use Illuminate\Support\Str;

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

    public function setLogo($logo): void
    {
        $this->openAPIJSON->info->{'x-logo'} = $logo;
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

    public function addRef(string $name): void
    {
        $components = $this->openAPIJSON->components;
        if (empty($components)) {
            $components = (object) [
                'schemas' => []
            ];
        }

        $class = new $name(1);
        $components->schemas[$class->name] = $class->getData();

        $this->openAPIJSON->components = $components;
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
        if (empty($this->openAPIJSON->servers) || $this->openAPIJSON->servers[0]->url == '/') {
            $servers = [];
        } else {
            $servers = $this->openAPIJSON->servers;
        }

        $servers[] = (object) [
            'url' => $url,
            'description' => $description,
        ];

        $this->openAPIJSON->servers = $servers;
    }

    function addRoute(Route $route) {

        // Verificar se existe o path
        if (!isset($this->openAPIJSON->paths[$route->path])) {
            $this->openAPIJSON->paths[$route->path] = new PathItem([]);
        }
        $path = &$this->openAPIJSON->paths[$route->path];

        $method = mb_strtolower($route->method);

        if (empty($path->{$method})) {
            $path->{$method} = new \cebe\openapi\spec\Operation([
                'tags' => $route->tags ?? [],
                'summary' => $route->title ?? '',
                'description' => $route->description ?? '',
                'parameters' => $route->parameters ?? [],
                'deprecated' => $route->deprecated ?? false,
                'security' => $route->security ?? [],
                'responses' => new \cebe\openapi\spec\Responses([])
            ]);
        }

        // Documentação externa
        if (!empty($route->externalDocs)) {
            $path->{$method}->externalDocs = $route->externalDocs;
        }

        /**
         * Uma rota pode ter diversos responses na mesma rota, por isso é necessário verificar se o response já existe
         * Se existir deve agrupar os responses em oneOf
         *
         * (Exemplo: Pagamento com cartão de crédito, boleto ou pix)
         */
        if (empty($path->{$method}->responses[$route->statusCode])) {
            $path->{$method}->responses = [
                $route->statusCode => [
                    'description' => $route->descriptionResponse,
                    'content' => [
                        $route->bodyType => [
                            'schema' => [
                                'title' => $route->variationName,
                                'description' => $route->variationDescription,
                                ...$this->dataToSchema($route->response, true)
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $body = $path->{$method}->responses[$route->statusCode]['content'][$route->bodyType]['schema'];
            $exist = !empty($path->{$method}->responses[$route->statusCode]['content'][$route->bodyType]['schema']['oneOf']);

            if (!$exist) {
                if (Helpers::isArrayAssoc($body)) {
                    $methodContent = $path->{$method}->responses;
                    $methodContent[$route->statusCode]['content'][$route->bodyType] = [
                        "schema" => [
                            "oneOf" => [
                                $body,
                                [
                                    'title' => $route->variationName,
                                    'description' => $route->variationDescription,
                                    ...$this->dataToSchema($route->response, true)
                                ]
                            ]
                        ]
                    ];
                    $path->{$method}->responses = $methodContent;
                }
            } else {
                $body = $path->{$method}->responses[$route->statusCode]['content'][$route->bodyType]['schema']['oneOf'];
                $methodContent = $path->{$method}->responses;
                $methodContent[$route->statusCode]['content'][$route->bodyType] = [
                    "schema" => [
                        "oneOf" => [
                            ...$body,
                            [
                                'title' => $route->variationName,
                                'description' => $route->variationDescription,
                                ...$this->dataToSchema($route->response, true)
                            ]
                        ]
                    ]
                ];
                $path->{$method}->responses = $methodContent;
            }
        }

        if (empty($route->bodyComplete)) {
            $route->bodyComplete = $route->body;
        }

        if (!empty($route->bodyComplete)) {

            $schema = $this->dataToSchema($route->bodyComplete, true, $route->bodyRequired, $route->bodyDescription, $route->bodyEnum);

            if (Str::contains(json_encode($schema), '"format":"binary"')) {
                $route->bodyType = 'multipart/form-data';
            }

            /**
             * Uma rota pode ter diversos body na mesma rota, por isso é necessário verificar se o response já existe
             * Se existir deve agrupar os body em oneOf
             *
             * (Exemplo: Pagamento com cartão de crédito, boleto ou pix)
             */
            if (empty($path->{$method}->requestBody)) {
                $path->{$method}->requestBody = [
                    'description' => $route->descriptionResponse,
                    'content' => [
                        $route->bodyType => [
                            'schema' => [
                                'title' => $route->variationName,
                                'description' => $route->variationDescription,
                                ...$schema
                            ]
                        ]
                    ]
                ];
            } else {
                if (empty($path->{$method}->requestBody['content'][$route->bodyType]['schema']['oneOf'])) {
                    $body = $path->{$method}->requestBody;
                    $body['content'][$route->bodyType]['schema'] = [
                        'oneOf' => [
                            $path->{$method}->requestBody['content'][$route->bodyType]['schema'],
                            [
                                'title' => $route->variationName,
                                'description' => $route->variationDescription,
                                ...$schema
                            ]
                        ]
                    ];
                    $path->{$method}->requestBody = $body;
                } else {
                    $body = $path->{$method}->requestBody;
                    $body['content'][$route->bodyType]['schema']['oneOf'] = [
                        ...$body['content'][$route->bodyType]['schema']['oneOf'],
                        [
                            'title' => $route->variationName,
                            'description' => $route->variationDescription,
                            ...$schema
                        ]
                    ];
                    $path->{$method}->requestBody = $body;
                }
            }
        }
    }

    function dataToSchema($data, $addExample = false, $required = [], $description = [], $enum = []) {

        $schema = [];
        if (is_object($data) || (is_array($data) && Helpers::isArrayAssoc($data))) {

            if (is_object($data)) {
                if (get_class($data) == \Illuminate\Http\Testing\File::class || get_class($data) == File::class) {
                    return [
                        'type' => 'string',
                        'format' => 'binary',
                    ];
                }else {
                    if (get_parent_class($data) == Ref::class) {
                        $this->addRef(get_class($data));

                        return [
                            '$ref' => '#/components/schemas/' . $data->name
                        ];
                    }
                }
            }

            $schema['type'] = 'object';
            $schema['properties'] = [];

            // Caso não tenha nenhum valor, transforma em objeto vazio
            if (empty($data)) {
                $schema['properties'] = (object) [];
            }

            foreach ($data as $key => $datum) {
                try {
                    $schema['properties'][$key] = $this->dataToSchema($datum, $addExample);
                }catch (\Exception $e) {
                    throw new \Exception("->{$key} {$e->getMessage()}");
                }

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

                if (gettype($data) == 'NULL') {
//                    throw new \Exception('Não é possível gerar documentação para um valor nulo');
                    // Provisório
                    $schema['type'] = 'string';
                }
                if (class_exists($data)) {
                    $class = app($data);
                    if ($class instanceof Ref) {
                        $schema = [];
                        $schema['$ref'] = '#/components/schemas/' . $class->name;
                    }
                }
            }

            if ($addExample && gettype($data) != 'NULL') {
                $schema['example'] = $data;
            }
        }

        if (!empty($required)) {
            $schema = $this->addRequiredToSchema($schema, $required);
        }


        if (!empty($description)) {
            $schema = $this->addDescriptionToSchema($schema, $description);
        }

        if (!empty($enum)) {
            $schema = $this->addDescriptionToEnum($schema, $enum);
        }

        return $schema;
    }

    function addRequiredToSchema($data, $required = []) {
        $data2 = new \Adbar\Dot($data);

        $keys = [];

        foreach ($required as $item) {
            $explode = explode('.', $item);
            $string = [];

            if ($explode[count($explode) - 1] == '0') {
                array_pop($explode);
            }

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

    function addDescriptionToSchema($data, $descriptions = []) {
        $data2 = new \Adbar\Dot($data);

        $descriptionsFixed = [];

        foreach ($descriptions as $keyOriginal => $value) {
            $explode = explode('.', $keyOriginal);
            $string = [];

            if ($explode[count($explode) - 1] == '*') {
                array_pop($explode);
            }

            foreach ($explode as $key => $item2) {
                if (is_numeric($item2) || $item2 == '*') {
                    $string[] = 'items';
                } else {
                    $string[] = 'properties';

                    $string[] = $item2;
                }
            }

            $descriptionsFixed[implode('.', $string)] = $value;
        }

        foreach ($descriptionsFixed as $key => $value) {
            $path = $data2->get($key);

            $keyRequired = '';
            if (!empty($path)) {
                $path['description'] = $value;
                $data2->set($key, $path);
            }

            // Para key vazia, deve enviar description para root do body
            if ($key == 'properties.') {
                $data2->set('description', $value);
            }
        }

        return $data2->all();
    }

    function addDescriptionToEnum($data, $enums = []) {
        $data2 = new \Adbar\Dot($data);

        $enumsFixed = [];

        foreach ($enums as $keyOriginal => $value) {
            $explode = explode('.', $keyOriginal);
            $string = [];

            if ($explode[count($explode) - 1] == '*') {
                array_pop($explode);
            }

            foreach ($explode as $key => $item2) {
                if (is_numeric($item2) || $item2 == '*') {
                    $string[] = 'items';
                } else {
                    $string[] = 'properties';

                    $string[] = $item2;
                }
            }

            $descriptionsFixed[implode('.', $string)] = $value;
        }

        foreach ($descriptionsFixed as $key => $value) {
            $path = $data2->get($key);

            $keyRequired = '';
            if (!empty($path)) {
                $path['enum'] = $value;
                $data2->set($key, $path);
            }

            // Para key vazia, deve enviar description para root do body
            if ($key == 'properties.') {
                $data2->set('enum', $value);
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
            self::$instance = new self($increment);
//            self::$instance->init($increment);
        }

        return self::$instance;
    }

    public static function clear() {
        self::$instance = null;
    }
}
