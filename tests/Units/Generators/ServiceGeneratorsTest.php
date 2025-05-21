<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\ServiceGenerator;
use Illuminate\Support\Facades\Config;

class ServiceGeneratorsTest extends TestCaseGenerator
{
    public ServiceGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ServiceGenerator::class);
    }

    public function testNamespaceService()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Services');
    }

    public function testServiceBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('service/service-simple.php', $this->generator->render());
    }

    public function testServiceBaseWithFields()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('service/service-with-fields.php', $this->generator->render());
    }
}
