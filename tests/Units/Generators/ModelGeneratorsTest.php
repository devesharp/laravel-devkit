<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\ModelGenerator;

class ModelGeneratorsTest extends \Tests\TestCase
{

    public ModelGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ModelGenerator::class);
    }

    public function testNamespaceModel()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Resources\Models');
    }

    public function testModelBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/model/model-simple.php'), $this->generator->render());
    }

    public function testModelBaseWithFile()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/model/model-with-file.php'), $this->generator->render());
    }

    public function testModelBaseWithPresenter()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'withPresenter' => true,
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/model/model-with-presenter.php'), $this->generator->render());
    }

    public function testModelBaseWithFactory()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'withFactory' => true,
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/model/model-with-factory.php'), $this->generator->render());
    }
}
