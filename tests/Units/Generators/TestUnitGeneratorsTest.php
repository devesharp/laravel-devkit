<?php

namespace Tests\Units\Generators;

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
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'Tests\Units\ModuleMain');
    }

    public function testTestUnitBase2()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/tests/test-unit-with-file.php'), $this->generator->render());
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

        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);
        
        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/tests/test-unit-simple.php'), $this->generator->render());
    }
}
