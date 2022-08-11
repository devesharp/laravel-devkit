<?php

namespace Tests\Units\TestDocsRoute;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\TestDocsRoute\Mocks\ValidatorStubWithGenerator;

class TestDocsRouteYmlTest extends \Tests\TestCase
{
    use \Devesharp\Testing\TestCase;

    protected function setUp(): void
    {
        parent::setUp();

        $apiDocs = \Devesharp\APIDocs\Generator::getInstance();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        \Route::middleware([])->post('/resource/search', function () {
            return [
                'results' => [
                    [
                        'id' => 1,
                        'name' => 'John',
                        'age' => '10',
                    ]
                ],
                'count' => 1,
            ];
        });
        \Route::middleware([])->get('/resource/{id}', function ($id) {
            return [
                'id' => $id,
                'name' => 'John',
                'age' => '10',
                'path' => request()->query(),
                'header' => request()->header(),
            ];
        });
        \Route::middleware([])->put('resource/{id}', function ($id) {
            return [
                'id' => $id,
                'name' => 'John',
                'age' => '10',
                'path' => request()->query(),
                'header' => request()->header(),
                'data' => request()->post(),
            ];
        });
        \Route::middleware([])->delete('resource/{id}', function ($id) {
            return [
                'id' => $id,
                'name' => 'John',
                'age' => '10',
                'path' => request()->query(),
                'header' => request()->header(),
                'data' => request()->post(),
            ];
        });
    }

    /**
     * @testdox testar test de rota como post
     */
    public function testSimplePostWithTest()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addGroups(['resources', 'posts'])
            ->addBody([
                'name' => 'John',
                'age' => 2,
            ])
            ->run();

        $yml = \Devesharp\APIDocs\Generator::getInstance()->toYml();

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  /resource/search:
    post:
      tags:
        - resources
        - posts
      summary: 'Create Post'
      description: 'method post'
      parameters: []
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  results:
                    type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: integer
                          format: int64
                          example: 1
                        name:
                          type: string
                          example: John
                        age:
                          type: string
                          example: '10'
                  count:
                    type: integer
                    format: int64
                    example: 1
      deprecated: false
      security: []
      requestBody:
        description: ''
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  example: John
                age:
                  type: integer
                  format: int64
                  example: 2
", $yml);
    }

    /**
     * @testdox testar test de rota como get
     */
    public function testSimpleGetWithTest()
    {
        $http = $this->withGet('/resource/:id')
            ->addPath('id', 10, 'ID da recurso')
            ->addQuery('platformId', 4, 'ID da plataforma', true)
            ->addQuery('userId', 8, 'ID do usuário', true)
            ->addHeader('X-API', 'Xsd283js9c29', 'API Key', true)
            ->addRouteName('Create Get')
            ->addGroups('resources')
            ->run();

        $yml = \Devesharp\APIDocs\Generator::getInstance()->toYml();

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  '/resource/{id}':
    get:
      tags:
        - resources
      summary: 'Create Get'
      description: ''
      parameters:
        -
          name: id
          in: path
          description: 'ID da recurso'
          required: true
          schema:
            type: string
          example: 10
        -
          name: platformId
          in: query
          description: 'ID da plataforma'
          required: true
          schema:
            type: string
          example: 4
        -
          name: userId
          in: query
          description: 'ID do usuário'
          required: true
          schema:
            type: string
          example: 8
        -
          name: X-API
          in: header
          description: 'API Key'
          required: true
          schema:
            type: string
          example: Xsd283js9c29
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:
                    type: string
                    example: '10'
                  name:
                    type: string
                    example: John
                  age:
                    type: string
                    example: '10'
                  path:
                    type: object
                    properties:
                      platformId:
                        type: string
                        example: '4'
                      userId:
                        type: string
                        example: '8'
                  header:
                    type: object
                    properties:
                      host:
                        type: array
                        items:
                          type: string
                          example: localhost
                      user-agent:
                        type: array
                        items:
                          type: string
                          example: Symfony
                      accept:
                        type: array
                        items:
                          type: string
                          example: 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                      accept-language:
                        type: array
                        items:
                          type: string
                          example: 'en-us,en;q=0.5'
                      accept-charset:
                        type: array
                        items:
                          type: string
                          example: 'ISO-8859-1,utf-8;q=0.7,*;q=0.7'
                      x-api:
                        type: array
                        items:
                          type: string
                          example: Xsd283js9c29
      deprecated: false
      security: []
", $yml);
    }

    /**
     * @testdox testar test de rota como delete
     */
    public function testSimpleDeleteWithTest()
    {
        $http = $this->withDelete('/resource/:id')
            ->addBody([
                'name' => 'John',
                'age' => 'John',
            ])
            ->addPath('id', 10, 'ID da recurso')
            ->addQuery('platformId', 4, 'ID da plataforma', true)
            ->addQuery('userId', 8, 'ID do usuário', true)
            ->addHeader('X-API', 'Xsd283js9c29', 'API Key', true)
            ->addRouteName('delete resource')
            ->addGroups('resources')
            ->run();

        $yml = \Devesharp\APIDocs\Generator::getInstance()->toYml();

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  '/resource/{id}':
    delete:
      tags:
        - resources
      summary: 'delete resource'
      description: ''
      parameters:
        -
          name: id
          in: path
          description: 'ID da recurso'
          required: true
          schema:
            type: string
          example: 10
        -
          name: platformId
          in: query
          description: 'ID da plataforma'
          required: true
          schema:
            type: string
          example: 4
        -
          name: userId
          in: query
          description: 'ID do usuário'
          required: true
          schema:
            type: string
          example: 8
        -
          name: X-API
          in: header
          description: 'API Key'
          required: true
          schema:
            type: string
          example: Xsd283js9c29
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:
                    type: string
                    example: '10'
                  name:
                    type: string
                    example: John
                  age:
                    type: string
                    example: '10'
                  path:
                    type: object
                    properties:
                      platformId:
                        type: string
                        example: '4'
                      userId:
                        type: string
                        example: '8'
                  header:
                    type: object
                    properties:
                      host:
                        type: array
                        items:
                          type: string
                          example: localhost
                      user-agent:
                        type: array
                        items:
                          type: string
                          example: Symfony
                      accept:
                        type: array
                        items:
                          type: string
                          example: 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                      accept-language:
                        type: array
                        items:
                          type: string
                          example: 'en-us,en;q=0.5'
                      accept-charset:
                        type: array
                        items:
                          type: string
                          example: 'ISO-8859-1,utf-8;q=0.7,*;q=0.7'
                      x-api:
                        type: array
                        items:
                          type: string
                          example: Xsd283js9c29
                      content-type:
                        type: array
                        items:
                          type: string
                          example: application/x-www-form-urlencoded
                  data:
                    type: object
                    properties:
                      name:
                        type: string
                        example: John
                      age:
                        type: string
                        example: John
      deprecated: false
      security: []
      requestBody:
        description: ''
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  example: John
                age:
                  type: string
                  example: John
", $yml);
    }

    /**
     * @testdox testar test de rota como put
     */
    public function testSimplePutWithTest()
    {
        $http = $this->withPut('/resource/:id')
            ->addBody([
                'name' => 'John',
                'age' => 'John',
            ])
            ->addPath('id', 10, 'ID da recurso')
            ->addQuery('platformId', 4, 'ID da plataforma', true)
            ->addQuery('userId', 8, 'ID do usuário', true)
            ->addHeader('X-API', 'Xsd283js9c29', 'API Key', true)
            ->addRouteName('delete resource')
            ->addGroups('resources')
            ->run();

        $yml = \Devesharp\APIDocs\Generator::getInstance()->toYml();

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  '/resource/{id}':
    put:
      tags:
        - resources
      summary: 'delete resource'
      description: ''
      parameters:
        -
          name: id
          in: path
          description: 'ID da recurso'
          required: true
          schema:
            type: string
          example: 10
        -
          name: platformId
          in: query
          description: 'ID da plataforma'
          required: true
          schema:
            type: string
          example: 4
        -
          name: userId
          in: query
          description: 'ID do usuário'
          required: true
          schema:
            type: string
          example: 8
        -
          name: X-API
          in: header
          description: 'API Key'
          required: true
          schema:
            type: string
          example: Xsd283js9c29
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:
                    type: string
                    example: '10'
                  name:
                    type: string
                    example: John
                  age:
                    type: string
                    example: '10'
                  path:
                    type: object
                    properties:
                      platformId:
                        type: string
                        example: '4'
                      userId:
                        type: string
                        example: '8'
                  header:
                    type: object
                    properties:
                      host:
                        type: array
                        items:
                          type: string
                          example: localhost
                      user-agent:
                        type: array
                        items:
                          type: string
                          example: Symfony
                      accept:
                        type: array
                        items:
                          type: string
                          example: 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                      accept-language:
                        type: array
                        items:
                          type: string
                          example: 'en-us,en;q=0.5'
                      accept-charset:
                        type: array
                        items:
                          type: string
                          example: 'ISO-8859-1,utf-8;q=0.7,*;q=0.7'
                      x-api:
                        type: array
                        items:
                          type: string
                          example: Xsd283js9c29
                      content-type:
                        type: array
                        items:
                          type: string
                          example: application/x-www-form-urlencoded
                  data:
                    type: object
                    properties:
                      name:
                        type: string
                        example: John
                      age:
                        type: string
                        example: John
      deprecated: false
      security: []
      requestBody:
        description: ''
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  example: John
                age:
                  type: string
                  example: John
", $yml);

    }

    /**
     * @testdox testar criação de rota com validator
     */
    public function testSimplePostWithValidatorTest()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addGroups(['resources', 'posts'])
            ->addBody([
                'name' => 'John',
                'age' => 2,
            ], ValidatorStubWithGenerator::class, 'complex', true)
            ->run();

        $yml = \Devesharp\APIDocs\Generator::getInstance()->toYml();

        $this->assertEquals("openapi: 3.0.2
info:
  title: 'API 1.0'
  description: 'API Example'
  version: 1.0.0
servers:
  -
    url: 'https://example.com.br'
    description: 'Prod API'
paths:
  /resource/search:
    post:
      tags:
        - resources
        - posts
      summary: 'Create Post'
      description: 'method post'
      parameters: []
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                type: object
                properties:
                  results:
                    type: array
                    items:
                      type: object
                      properties:
                        id:
                          type: integer
                          format: int64
                          example: 1
                        name:
                          type: string
                          example: John
                        age:
                          type: string
                          example: '10'
                  count:
                    type: integer
                    format: int64
                    example: 1
      deprecated: false
      security: []
      requestBody:
        description: ''
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  example: John
                  description: Nome
                age:
                  type: integer
                  format: int64
                  example: 2
                  description: Idade
                active:
                  type: string
                  example: string
                  description: Ativo
                pets:
                  type: array
                  items:
                    type: object
                    properties:
                      id:
                        type: string
                        example: string
                        description: ID
                      name:
                        type: string
                        example: string
                        description: 'Nome do Pet'
                    required:
                      - id
                      - name
                  description: ID
                owner:
                  type: object
                  properties:
                    id:
                      type: string
                      example: string
                      description: 'ID do Dono'
                    name:
                      type: string
                      example: string
                      description: 'Nome do Dono'
                    age:
                      type: string
                      example: string
                      description: 'Idade do Dono'
                item_array_deep:
                  type: array
                  items:
                    type: object
                    properties:
                      id:
                        type: string
                        example: string
                      name:
                        type: string
                        example: string
                      items:
                        type: array
                        items:
                          type: object
                          properties:
                            id:
                              type: string
                              example: string
                            name:
                              type: string
                              example: string
                    required:
                      - items
              required:
                - pets
", $yml);
    }
}
