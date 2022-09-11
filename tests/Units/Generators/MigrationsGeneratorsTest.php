<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\DtoGenerator;
use Devesharp\Generators\FactoryGenerator;
use Devesharp\Generators\MigrationGenerator;

class MigrationsGeneratorsTest extends \Tests\TestCase
{

    public MigrationGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(MigrationGenerator::class);
    }

    public function testMigrationBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/migration/migration-simple.php'), $this->generator->render());
    }
}
