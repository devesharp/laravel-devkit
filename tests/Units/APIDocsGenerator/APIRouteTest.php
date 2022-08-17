<?php

namespace Tests\Units\APIDocsGenerator;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\APIDocsGenerator\Mocks\ValidatorStubWithGenerator;

class APIRouteTest extends \Tests\TestCase
{
    /**
     * @testdox post - json
     */
    public function testDefaultGet()
    {
        $apiDocs = new \Devesharp\APIDocs\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setTermsOfService('http://example.com/terms/');
        $apiDocs->setContact("API Support", "http//www.example.com/support", "support@example.com");
        $apiDocs->setLicense("Apache 2.0", "https://www.apache.org/licenses/LICENSE-2.0.html");
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        $data = new \Devesharp\APIDocs\Utils\Get();
        $data->path = '/pets';
        $data->tags = ['pets', 'get'];
        $data->summary = 'Find pets by ID';
        $data->description = 'Returns pets based on ID';
        $data->externalDocs = [
            "description" =>  "Find more info here",
            "url" =>  "https://example.com",
        ];
        $data->deprecated = true;
        $data->parameters = [
            [
                'name' => 'platformId',
                'in' => 'query',
                'required' => true,
                'description' => 'Platform ID',
                'schema' => [
                    'type' => 'integer',
                    'format' => 'int64',
                ]
            ],
        ];
        $data->response = [
            'key_string' => 'string',
        ];

        $apiDocs->addRoute($data);

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  termsOfService: 'http://example.com/terms/'
  contact:
    name: 'API Support'
    url: http//www.example.com/support
    email: support@example.com
  license:
    name: 'Apache 2.0'
    url: 'https://www.apache.org/licenses/LICENSE-2.0.html'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  /pets:
    get:
      tags:
        - pets
        - get
      summary: 'Find pets by ID'
      description: 'Returns pets based on ID'
      parameters:
        -
          name: platformId
          in: query
          description: 'Platform ID'
          required: true
          schema:
            type: integer
            format: int64
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  key_string:
                    type: string
                    example: string
      deprecated: true
      security: []
      externalDocs:
        description: 'Find more info here'
        url: 'https://example.com'
", $apiDocs->toYml());
    }

    /**
     * @testdox post - json
     */
    public function testDefaultPost2()
    {
        $apiDocs = new \Devesharp\APIDocs\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setTermsOfService('http://example.com/terms/');
        $apiDocs->setContact("API Support", "http//www.example.com/support", "support@example.com");
        $apiDocs->setLicense("Apache 2.0", "https://www.apache.org/licenses/LICENSE-2.0.html");
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        $data = new \Devesharp\APIDocs\Utils\Post();
        $data->path = '/pets';
        $data->tags = ['pets', 'get'];
        $data->summary = 'Find pets by ID';
        $data->description = 'Returns pets based on ID';
        $data->externalDocs = [
            "description" =>  "Find more info here",
            "url" =>  "https://example.com",
        ];
        $data->deprecated = true;
        $data->parameters = [
            [
                'name' => 'platformId',
                'in' => 'query',
                'required' => true,
                'description' => 'Platform ID',
                'schema' => [
                    'type' => 'integer',
                    'format' => 'int64',
                ]
            ],
        ];
        $data->body = [
            'key_string' => 'string',
            'key_array' => ['string', 'string'],
        ];
        $data->bodyRequired = [
            'key_string',
        ];

        $data->response = [
            'key_string' => 'string',
        ];

        $apiDocs->addRoute($data);

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  termsOfService: 'http://example.com/terms/'
  contact:
    name: 'API Support'
    url: http//www.example.com/support
    email: support@example.com
  license:
    name: 'Apache 2.0'
    url: 'https://www.apache.org/licenses/LICENSE-2.0.html'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  /pets:
    post:
      tags:
        - pets
        - get
      summary: 'Find pets by ID'
      description: 'Returns pets based on ID'
      parameters:
        -
          name: platformId
          in: query
          description: 'Platform ID'
          required: true
          schema:
            type: integer
            format: int64
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  key_string:
                    type: string
                    example: string
      deprecated: true
      security: []
      externalDocs:
        description: 'Find more info here'
        url: 'https://example.com'
      requestBody:
        description: ''
        content:
          application/json:
            schema:
              type: object
              properties:
                key_string:
                  type: string
                  example: string
                key_array:
                  type: array
                  items:
                    type: string
                    example: string
              required:
                - key_string
", $apiDocs->toYml());
    }

    /**
     * @testdox post - multipart/form-data
     */
    public function testDefaultPostFormData()
    {
        $apiDocs = new \Devesharp\APIDocs\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addBasicAuth('basicAuth');

        $data = new \Devesharp\APIDocs\Utils\Post();
        $data->path = '/upload';
        $data->bodyType = 'multipart/form-data';
        $data->tags = ['pets', 'get'];
        $data->summary = 'Find pets by ID';
        $data->description = 'Returns pets based on ID';
        $data->externalDocs = [
            "description" =>  "Find more info here",
            "url" =>  "https://example.com",
        ];
        $data->deprecated = true;
        $data->parameters = [
            [
                'name' => 'platformId',
                'in' => 'query',
                'required' => true,
                'description' => 'Platform ID',
                'schema' => [
                    'type' => 'integer',
                    'format' => 'int64',
                ]
            ],
        ];
        $data->body = [
            'file' => UploadedFile::fake()->image('perfil-100x100.png'),
            'key_array' => ['string', 'string'],
        ];
        $data->bodyRequired = [
            'file',
        ];

        $data->response = [
            'key_string' => 'string',
        ];

        $data->security = [
            [
                'basicAuth' => [],
            ]
        ];

        $apiDocs->addRoute($data);

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  version: 1.0.0
servers: []
paths:
  /upload:
    post:
      tags:
        - pets
        - get
      summary: 'Find pets by ID'
      description: 'Returns pets based on ID'
      parameters:
        -
          name: platformId
          in: query
          description: 'Platform ID'
          required: true
          schema:
            type: integer
            format: int64
      responses:
        '200':
          description: ''
          content:
            multipart/form-data:
              schema:
                type: object
                properties:
                  key_string:
                    type: string
                    example: string
      deprecated: true
      security:
        -
          basicAuth: []
      externalDocs:
        description: 'Find more info here'
        url: 'https://example.com'
      requestBody:
        description: ''
        content:
          multipart/form-data:
            schema:
              type: object
              properties:
                file:
                  type: string
                  format: binary
                key_array:
                  type: array
                  items:
                    type: string
                    example: string
              required:
                - file
components:
  securitySchemes:
    basicAuth:
      type: http
      description: 'Bearer Authentication'
      scheme: basic
", $apiDocs->toYml());
    }
}
