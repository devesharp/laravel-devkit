<?php

namespace Tests\Units\SwaggerGenerator;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\SwaggerGenerator\Mocks\ValidatorStubWithGenerator;

class SwaggerRouteTest extends \Tests\TestCase
{
    /**
     * @testdox swaggetGenerator: Testando a geração de documentação de API como GET
     */
    public function testDeGetGenerate()
    {
        $apiDocs = new \Devesharp\SwaggerGenerator\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setTermsOfService('http://example.com/terms/');
        $apiDocs->setContact("API Support", "http//www.example.com/support", "support@example.com");
        $apiDocs->setLicense("Apache 2.0", "https://www.apache.org/licenses/LICENSE-2.0.html");
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        $data = new \Devesharp\SwaggerGenerator\Utils\Route();
        $data->method = 'GET';
        $data->path = '/pets';
        $data->tags = ['pets', 'get'];
        $data->title = 'Find pets by ID';
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

        $this->assertEquals(file_get_contents(__DIR__ . '/compare/get.yml'), $apiDocs->toYml());
    }

    /**
     * @testdox swaggetGenerator: Testando a geração de documentação de API como POST
     */
    public function testDePostGenerate()
    {
        $apiDocs = new \Devesharp\SwaggerGenerator\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setTermsOfService('http://example.com/terms/');
        $apiDocs->setContact("API Support", "http//www.example.com/support", "support@example.com");
        $apiDocs->setLicense("Apache 2.0", "https://www.apache.org/licenses/LICENSE-2.0.html");
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        $data = new \Devesharp\SwaggerGenerator\Utils\Route();
        $data->method = 'POST';
        $data->path = '/pets';
        $data->tags = ['pets', 'get'];
        $data->title = 'Find pets by ID';
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

        $this->assertEquals(file_get_contents(__DIR__ . '/compare/post.yml'), $apiDocs->toYml());
    }

    /**
     * @testdox swaggetGenerator: Testando a geração de documentação de API como POST com multipart/form-data
     */
    public function testDePostMultipartGenerate()
    {
        $apiDocs = new \Devesharp\SwaggerGenerator\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addBasicAuth('basicAuth');

        $data = new \Devesharp\SwaggerGenerator\Utils\Route();
        $data->setMethod('POST');
        $data->setPath('/upload');
        $data->setBodyType('multipart/form-data');
        $data->setTags(['pets', 'get']);
        $data->setTitle('Find pets by ID', 'Returns pets based on ID');
        $data->setExternalDocs("Find more info here", "https://example.com");
        $data->setDeprecated();
        $data->setParameters([
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
        ]);
        $data->setBody([
            'file' => UploadedFile::fake()->image('perfil-100x100.png'),
            'key_array' => ['string', 'string'],
        ]);

        $data->setBodyRequired([
            'file',
        ]);

        $data->setResponse([
            'key_string' => 'string',
        ]);

        $data->setSecurity([
            [
                'basicAuth' => [],
            ]
        ]);;
        $apiDocs->addRoute($data);

        $this->assertEquals(file_get_contents(__DIR__ . '/compare/post-multipart-form-data.yml'), $apiDocs->toYml());
    }
}
