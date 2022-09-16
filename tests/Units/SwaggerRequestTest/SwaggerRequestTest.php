<?php

namespace Tests\Units\SwaggerRequestTest;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\APIDocsGenerator\Mocks\ValidatorStubWithGenerator;

class SwaggerRequestTest extends \Tests\TestCase
{
    use \Devesharp\Testing\TestCase;

    protected function setUp(): void
    {
        parent::setUp();

//        \Devesharp\APIDocs\APIDocsCreate::getInstance()->init();

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
            ->addRouteName('Create Post')
            ->addGroups('resources')
            ->addBody([
                'name' => 'John',
                'age' => 'John',
            ])
            ->run();

        $this->assertEquals($http->getStatusCode(), 200);
        $this->assertEquals(json_decode((string) $http->getContent(), true), [
            'results' => [
                [
                    'id' => 1,
                    'name' => 'John',
                    'age' => '10',
                ]
            ],
            'count' => 1,
        ]);
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

        $this->assertEquals($http->getStatusCode(), 200);
        $this->assertEquals(json_decode((string) $http->getContent(), true), [
            // Deve adicionar params de Id na rota
            'id' => '10',
            // Deve adicionar valores ao path ?platformId=4&userId=8
            'path' => [
                'platformId' => '4',
                'userId' => '8',
            ],
            // Deve adicionar header
            'header' => [
                "host" => ["localhost"],
                "user-agent" => ["Symfony"],
                "accept" => ["text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"],
                "accept-language" => ["en-us,en;q=0.5"],
                "accept-charset" => ["ISO-8859-1,utf-8;q=0.7,*;q=0.7"],
                // Custom valor
                "x-api" => ["Xsd283js9c29"],
            ],
            // Outros
            'name' => 'John',
            'age' => '10',
        ]);
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

        $this->assertEquals(json_decode((string) $http->getContent(), true), [
            // Deve adicionar params de Id na rota
            'id' => '10',
            // Deve adicionar valores ao path ?platformId=4&userId=8
            'path' => [
                'platformId' => '4',
                'userId' => '8',
            ],
            // Deve adicionar header
            'header' => [
                "host" => ["localhost"],
                "user-agent" => ["Symfony"],
                "accept" => ["text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"],
                "accept-language" => ["en-us,en;q=0.5"],
                "accept-charset" => ["ISO-8859-1,utf-8;q=0.7,*;q=0.7"],
                "content-type" => ["application/x-www-form-urlencoded"],
                // Custom valor
                "x-api" => ["Xsd283js9c29"],
            ],
            'data' => [
                'name' => 'John',
                'age' => 'John',
            ],
            // Outros
            'name' => 'John',
            'age' => '10',
        ]);

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

        $this->assertEquals(json_decode((string) $http->getContent(), true), [
            // Deve adicionar params de Id na rota
            'id' => '10',
            // Deve adicionar valores ao path ?platformId=4&userId=8
            'path' => [
                'platformId' => '4',
                'userId' => '8',
            ],
            // Deve adicionar header
            'header' => [
                "host" => ["localhost"],
                "user-agent" => ["Symfony"],
                "accept" => ["text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"],
                "accept-language" => ["en-us,en;q=0.5"],
                "accept-charset" => ["ISO-8859-1,utf-8;q=0.7,*;q=0.7"],
                "content-type" => ["application/x-www-form-urlencoded"],
                // Custom valor
                "x-api" => ["Xsd283js9c29"],
            ],
            'data' => [
                'name' => 'John',
                'age' => 'John',
            ],
            // Outros
            'name' => 'John',
            'age' => '10',
        ]);

    }
}
