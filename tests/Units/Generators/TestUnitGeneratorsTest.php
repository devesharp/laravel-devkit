<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\TestUnitGenerator;
use Illuminate\Support\Facades\Config;

class TestUnitGeneratorsTest extends TestCaseGenerator
{
    public TestUnitGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(TestUnitGenerator::class);
    }

    public function testNamespaceTestUnit()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'Tests\Units\Products');
    }

    public function testTestUnitBase2()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('tests/test-unit-with-file.php', $this->generator->render());
    }

    public function testTestUnitBaseWithUserRelation()
    {
        Config::set('devesharp_generator.relations', [
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
        
        $this->assertTemplate('tests/test-unit-with-complex-relations.php', $this->generator->render());
    }
}
