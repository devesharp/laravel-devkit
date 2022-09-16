<?php

namespace Tests\Units\SwaggerGenerator;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;

class SwaggerGeneratorInfoTest extends \Tests\TestCase
{
    /**
     * @testdox Test header docs
     */
    public function testHeaderDocs()
    {
        $apiDocs = new \Devesharp\SwaggerGenerator\Generator();
        $apiDocs->setTitle('API 1.0');
        $apiDocs->setDescription('API Example');
        $apiDocs->setTermsOfService('http://example.com/terms/');
        $apiDocs->setContact("API Support", "http =>//www.example.com/support", "support@example.com");
        $apiDocs->setLicense("Apache 2.0", "https://www.apache.org/licenses/LICENSE-2.0.html");
        $apiDocs->setVersion('1.0.0');
        $apiDocs->addServers('https://example.com.br', 'Prod API');

        $openApi = new \cebe\openapi\spec\OpenApi([
            'openapi' => '3.0.2',
            'info' => new \cebe\openapi\spec\Info([
                "title" => 'API 1.0',
                "description" => 'API Example',
                "version" => "1.0.0",
                "termsOfService" => "http://example.com/terms/",
                "contact" => [
                    "name" => "API Support",
                    "url" => "http =>//www.example.com/support",
                    "email" => "support@example.com"
                ],
                "license" => [
                    "name" => "Apache 2.0",
                    "url" => "https://www.apache.org/licenses/LICENSE-2.0.html"
                ],
            ]),
            "servers" => [
                [
                    'url' => 'https://example.com.br',
                    'description' => 'Prod API'
                ]
            ],
            'paths' => [],
        ]);

        $this->assertEquals(\cebe\openapi\Writer::writeToYaml($openApi), $apiDocs->toYml());
    }
}
