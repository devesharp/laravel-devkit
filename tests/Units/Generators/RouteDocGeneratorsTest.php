<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\RouteDocGenerator;
use Illuminate\Support\Facades\Config;

class RouteDocGeneratorsTest extends \Tests\TestCase
{
    public RouteDocGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(RouteDocGenerator::class);
    }

    public function testNamespaceRouteDoc()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Supports\Docs');
    }

    public function testRouteDocBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/docs/route-doc-simple.php'), $this->generator->render());
    }
}
