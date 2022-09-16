<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\DtoGenerator;
use Devesharp\Generators\FactoryGenerator;

class FactoryGeneratorsTest extends \Tests\TestCase
{

    public FactoryGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(FactoryGenerator::class);
    }

    public function testNamespaceDto()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Resources\Factories');
    }

    public function testDtoBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/factory/factory-simple.php'), $this->generator->render());
    }

    public function testDtoWithFile()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/factory/factory-with-file.php'), $this->generator->render());
    }

}
