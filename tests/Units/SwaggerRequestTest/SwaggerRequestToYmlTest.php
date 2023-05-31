<?php

namespace Tests\Units\SwaggerRequestTest;

use Illuminate\Http\UploadedFile;
use Tests\Units\SwaggerRequestTest\Mocks\ComplexDtoStub;
use Tests\Units\SwaggerRequestTest\Mocks\DtoWithEnumStub;
use Tests\Units\SwaggerRequestTest\Mocks\RefTest;

class SwaggerRequestToYmlTest extends \Tests\TestCase
{
    use \Devesharp\Testing\TestCase;

    protected function setUp(): void
    {
        parent::setUp();

        \Devesharp\SwaggerGenerator\Generator::clear();

        $apiDocs = \Devesharp\SwaggerGenerator\Generator::getInstance();
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

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
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

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
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

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
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
     * @testdox testar test de rota como put com arquivo, deve converter request para multipart/form-data
     */
    public function testSimplePutWithTestWithFile()
    {
        $http = $this->withPut('/resource/:id')
            ->addBody([
                'file' => UploadedFile::fake()->image('perfil-100x100.png'),
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

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
          multipart/form-data:
            schema:
              title: ''
              description: ''
              type: object
              properties:
                file:
                  type: string
                  format: binary
                name:
                  type: string
                  example: John
                age:
                  type: string
                  example: John
", $yml);

    }

    /**
     * @testdox testar test de rota com ref
     */
    public function testRouteWithTestRef()
    {
        $apiDocs = \Devesharp\SwaggerGenerator\Generator::getInstance();

        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addGroups(['resources', 'posts'])
            ->addBody([
                'name' => 'John',
                'property_type' => new RefTest('rent'),
            ])
            ->run();


        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
              type: object
              properties:
                name:
                  type: string
                  example: John
                property_type:
                  \$ref: '#/components/schemas/PropertyType'
components:
  schemas:
    PropertyType:
      type: string
      enum:
        - rent
        - sale
      description: 'Tipo de Imóvel'
", $yml);
    }


    /**
     * @testdox gerar duas rotas com mesmo nome (variação)
     */
    public function testSimplePostWithTestTwo()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search Name', 'Description for search Name')
            ->addGroups(['resources'])
            ->addBody([
                'name' => 'John',
            ])
            ->run();

        $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search Age', 'Description for search Age')
            ->addGroups(['resources'])
            ->addBody([
                'age' => 10,
            ])
            ->run();

        $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search active', 'Description for search active')
            ->addGroups(['resources'])
            ->addBody([
                'active' => false,
            ])
            ->run();

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
      summary: 'Create Post'
      description: 'method post'
      parameters: []
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                oneOf:
                  -
                    title: 'Search Name'
                    description: 'Description for search Name'
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
                  -
                    title: 'Search Age'
                    description: 'Description for search Age'
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
                  -
                    title: 'Search active'
                    description: 'Description for search active'
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
              oneOf:
                -
                  title: 'Search Name'
                  description: 'Description for search Name'
                  type: object
                  properties:
                    name:
                      type: string
                      example: John
                -
                  title: 'Search Age'
                  description: 'Description for search Age'
                  type: object
                  properties:
                    age:
                      type: integer
                      format: int64
                      example: 10
                -
                  title: 'Search active'
                  description: 'Description for search active'
                  type: object
                  properties:
                    active:
                      type: boolean
                      example: false
", $yml);
    }

    /**
     * @testdox gerar duas rotas com mesmo nome (variação), porém com mesmo body
     */
    public function testSimplePostWithTestTwoSameBody()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search Name', 'Description for search Name')
            ->addGroups(['resources'])
            ->addBody([
                'name' => 'John',
            ])
            ->run();

        $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search Age', 'Description for search Age')
            ->addGroups(['resources'])
            ->addBody([
                'age' => 10,
            ])
            ->ignoreDuplicateBody()
            ->run();

        $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addVaritionName('Search active', 'Description for search active')
            ->addGroups(['resources'])
            ->addBody([
                'active' => false,
            ])
            ->ignoreDuplicateBody()
            ->run();

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
      summary: 'Create Post'
      description: 'method post'
      parameters: []
      responses:
        '200':
          description: ''
          content:
            application/json:
              schema:
                oneOf:
                  -
                    title: 'Search Name'
                    description: 'Description for search Name'
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
                  -
                    title: 'Search Age'
                    description: 'Description for search Age'
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
                  -
                    title: 'Search active'
                    description: 'Description for search active'
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
              title: 'Search Name'
              description: 'Description for search Name'
              type: object
              properties:
                name:
                  type: string
                  example: John
", $yml);
    }

    /**
     * @testdox dto - completar todos os dados com dto
     */
    public function testSimplePostWithDtoTest()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addGroups(['resources', 'posts'])
            ->addBody([
                'name' => 'John',
                'age' => 2,
                'pets' => [
                    [
                        'id' => 1,
                        'name' => 'Dog'
                    ]
                ],
                'owner' => [
                    'id' => 1,
                    'name' => 'Master Dog'
                ],
                'item_array_deep' => [
                    [
                        'id' => 1,
                        'items' => [
                            [
                                'id' => 1,
                                'name' => 'Dog'
                            ]
                        ]
                    ]
                ]
            ], ComplexDtoStub::class)
            ->run();

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
              type: object
              properties:
                pets:
                  type: array
                  items:
                    type: object
                    properties:
                      id:
                        type: integer
                        format: int64
                        example: 1
                        description: ID
                      name:
                        type: string
                        example: Dog
                        description: 'Nome do Pet'
                    required:
                      - id
                      - name
                  description: 'Animais do usuário'
                owner:
                  type: object
                  properties:
                    id:
                      type: integer
                      format: int64
                      example: 1
                      description: 'ID do Dono'
                    name:
                      type: string
                      example: 'Master Dog'
                      description: 'Nome do Dono'
                    age:
                      type: string
                      example: string
                      description: 'Idade do Dono'
                  description: 'Dados do Dono'
                item_array_deep:
                  type: array
                  items:
                    type: object
                    properties:
                      id:
                        type: integer
                        format: int64
                        example: 1
                      items:
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
                              example: Dog
                      name:
                        type: string
                        example: string
                    required:
                      - items
              required:
                - pets
                - owner
                - item_array_deep
", $yml);
    }

    /**
     * @testdox dto - completar com enum
     */
    public function testSimplePostWithDtoEnumTest()
    {
        $http = $this->withPost('/resource/search')
            ->addRouteName('Create Post', 'method post')
            ->addGroups(['resources', 'posts'])
            ->addBody([
                'category' => 'apartment',
                'type' => 'rent',
            ], DtoWithEnumStub::class)
            ->run();

        $yml = \Devesharp\SwaggerGenerator\Generator::getInstance()->toYml();

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
                title: ''
                description: ''
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
              title: ''
              description: ''
              type: object
              properties:
                category:
                  type: string
                  example: apartment
                  description: 'Categoria do produto'
                  enum:
                    - apartment
                    - house
                    - commercial
                type:
                  type: string
                  example: rent
                  enum:
                    - rent
                    - sale
                    - exchange
", $yml);
    }
}
