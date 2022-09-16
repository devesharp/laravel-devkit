<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\ServiceGenerator;
use Illuminate\Support\Facades\Config;

class ServiceGeneratorsTest extends \Tests\TestCase
{
    public ServiceGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ServiceGenerator::class);
    }

    public function testNamespaceService()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Services');
    }

    public function testServiceBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/service/service-simple.php'), $this->generator->render());
    }
}
