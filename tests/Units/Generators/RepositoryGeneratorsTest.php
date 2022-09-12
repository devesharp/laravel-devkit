<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\RepositoryGenerator;

class RepositoryGeneratorsTest extends \Tests\TestCase
{

    public RepositoryGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(RepositoryGenerator::class);
    }

    public function testNamespaceRepository()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Resources\Repository');
    }

    public function testRepositoryBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/repository/repository-simple.php'), $this->generator->render());
    }
}
