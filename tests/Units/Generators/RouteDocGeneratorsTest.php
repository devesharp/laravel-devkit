<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\RouteDocGenerator;
use Illuminate\Support\Facades\Config;

class RouteDocGeneratorsTest extends TestCaseGenerator
{
    public RouteDocGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(RouteDocGenerator::class);
    }

    public function testNamespaceRouteDoc()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate($this->generator->getNamespace(), 'App\Modules\ModuleMain\Supports\Docs');
    }

    public function testRouteDocBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('/docs/route-doc-simple.php', $this->generator->render());
    }

    public function testRouteDocWithName()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
            resourceGramaticalName: 'Eletrônicos',
        ));

        $this->assertTemplate('/docs/route-doc-gramatical-name.php', $this->generator->render());
    }
}
