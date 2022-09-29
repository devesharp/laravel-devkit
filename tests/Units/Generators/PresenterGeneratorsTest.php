<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\PresenterGenerator;

class PresenterGeneratorsTest extends TestCaseGenerator
{
    public PresenterGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(PresenterGenerator::class);
    }

    public function testNamespacePresenter()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Resources\Presenters');
    }

    public function testPresenterBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('presenter/presenter-simple.php', $this->generator->render());
    }

}
