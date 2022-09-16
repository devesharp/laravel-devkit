<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\TestRouteGenerator;
use Illuminate\Support\Facades\Config;

class TestRouteGeneratorsTest extends \Tests\TestCase
{

    public TestRouteGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(TestRouteGenerator::class);
    }

    public function testNamespaceTestRoute()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'Tests\Routes\ModuleMain');
    }

    public function testTestRouteBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/tests/test-route-simple.php'), $this->generator->render());
    }

    public function testTestRouteBaseWithUserRelation()
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

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/tests/test-route-with-user-relation.php'), $this->generator->render());
    }
}
