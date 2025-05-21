<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\ModelGenerator;

class ModelGeneratorsTest extends TestCaseGenerator
{

    public ModelGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ModelGenerator::class);
    }

    public function testNamespaceModel()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Resources\Models');
    }

    public function testModelBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('model/model-simple.php', $this->generator->render());
    }

    public function testModelBaseWithFile()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('model/model-with-file.php', $this->generator->render());
    }

    public function testModelBaseWithPresenter()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
            withPresenter: true,
        ));

        $this->assertTemplate('model/model-with-presenter.php', $this->generator->render());
    }

    public function testModelBaseWithFactory()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
            withFactory: true,
        ));

        $this->assertTemplate('model/model-with-factory.php', $this->generator->render());
    }
}
