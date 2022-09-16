<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\DtoGenerator;

class DtoGeneratorsTest extends \Tests\TestCase
{

    public DtoGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(DtoGenerator::class);
    }

    public function testNamespaceDto()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Dtos');
    }

    public function testDtoBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample'
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/dto/dto-simple.php'), $this->generator->render());
    }

    public function testDtoBaseWithFile()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/dto/dto-with-file.php'), $this->generator->render());
    }

    public function testDtoSearchTemplate()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ], [
            'template' => 'search',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/dto/dto-create-template.php'), $this->generator->render());
    }

    public function testDtoDeleteTemplate()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ], [
            'template' => 'delete',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/dto/dto-delete-template.php'), $this->generator->render());
    }

    public function testDtoUpdateTemplate()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ], [
            'template' => 'update',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/dto/dto-update-template.php'), $this->generator->render());
    }

}
