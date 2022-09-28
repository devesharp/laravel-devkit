<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\DtoGenerator;

class DtoGeneratorsTest extends TestCaseGenerator
{

    public DtoGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(DtoGenerator::class);
    }

    public function testNamespaceDto()
    {
        $data = new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        );
        $this->generator->setTemplateData($data);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Dtos');
    }

    public function testDtoBase()
    {
        $data = new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        );
        $this->generator->setTemplateData($data);

        $this->assertTemplate('dto/dto-simple.php', $this->generator->render());
    }

    public function testDtoBaseWithFile()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('dto/dto-with-file.php', $this->generator->render());
    }

    public function testDtoSearchTemplate()
    {
        $this->generator->setTemplateData(
            TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'),
            [
                'template' => 'search',
            ]
        );

        $this->assertTemplate('dto/dto-create-template.php', $this->generator->render());
    }

    public function testDtoDeleteTemplate()
    {
        $this->generator->setTemplateData(
            TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'),
            [
                'template' => 'delete',
            ]
        );

        $this->assertTemplate('dto/dto-delete-template.php', $this->generator->render());
    }

    public function testDtoUpdateTemplate()
    {
        $this->generator->setTemplateData(
            TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'),
            [
                'template' => 'update',
            ]
        );

        $this->assertTemplate('dto/dto-update-template.php', $this->generator->render());
    }

}
