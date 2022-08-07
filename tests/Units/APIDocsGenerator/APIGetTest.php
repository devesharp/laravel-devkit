<?php

namespace Tests\Units\APIDocsGenerator;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\APIDocsGenerator\Mocks\ValidatorStubWithGenerator;

class APIGetTest extends \Tests\TestCase
{
    /**
     * @testdox Test header docs
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

//        $data->data = [];
//        $data->validatorClass = ValidatorStubWithGenerator::class;
        var_dump($apiDocs->toYml());

        die();

        $openApi = new \cebe\openapi\spec\OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([]),
            "servers" => [],
            'paths' => [],
        ]);


        $this->assertEquals(\cebe\openapi\Writer::writeToYaml($openApi), $apiDocs->toYml());
    }

    public function testDefaultPost()
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

//        $data->data = [];
//        $data->validatorClass = ValidatorStubWithGenerator::class;
        var_dump($apiDocs->toYml());

        die();

        $openApi = new \cebe\openapi\spec\OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([]),
            "servers" => [],
            'paths' => [],
        ]);


        $this->assertEquals(\cebe\openapi\Writer::writeToYaml($openApi), $apiDocs->toYml());
    }
}
