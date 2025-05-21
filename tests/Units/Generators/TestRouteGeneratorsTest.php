<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\TestRouteGenerator;
use Illuminate\Support\Facades\Config;

class TestRouteGeneratorsTest extends TestCaseGenerator
{
    public TestRouteGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(TestRouteGenerator::class);
    }

    public function testNamespaceTestRoute()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'Tests\Routes\Products');
    }

    public function testTestRouteBase()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('tests/test-route-simple.php', $this->generator->render());
    }

    public function testTestRouteBaseWithUserRelation()
    {
        Config::set('devesharp_dev_kit.relations', [
            "Users" => [
                "platform_id" => [
                    "resource" => "Platforms",
                    "field" => "id",
                ],
                "type_id" => [
                    "resource" => "UsersTypes",
                    "field" => "id",
                ],
            ],
            "Platforms" => [
                "system_id" => [
                    "resource" => "System",
                    "field" => "id",
                ]
            ],
        ]);

        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('tests/test-route-with-user-relation.php', $this->generator->render());
    }
}
