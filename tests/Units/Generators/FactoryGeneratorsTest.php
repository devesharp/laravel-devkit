<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\FactoryGenerator;

class FactoryGeneratorsTest extends TestCaseGenerator
{
    public FactoryGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(FactoryGenerator::class);
    }

    public function testNamespaceFaker()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Resources\Factories');
    }

    public function testFakerBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('factory/factory-simple.php', $this->generator->render());
    }

    public function testFakerWithFile()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('factory/factory-with-file.php', $this->generator->render());
    }

}
