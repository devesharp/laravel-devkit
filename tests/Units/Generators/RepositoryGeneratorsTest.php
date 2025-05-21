<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\RepositoryGenerator;

class RepositoryGeneratorsTest extends TestCaseGenerator
{

    public RepositoryGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(RepositoryGenerator::class);
    }

    public function testNamespaceRepository()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Resources\Repositories');
    }

    public function testRepositoryBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('repository/repository-simple.php', $this->generator->render());
    }
}
