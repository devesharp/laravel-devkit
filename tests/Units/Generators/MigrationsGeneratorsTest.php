<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\DtoGenerator;
use Devesharp\Generators\FactoryGenerator;
use Devesharp\Generators\MigrationGenerator;

class MigrationsGeneratorsTest extends TestCaseGenerator
{

    public MigrationGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(MigrationGenerator::class);
    }

    public function testMigrationBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('migration/migration-simple.php', $this->generator->render());
    }

    public function testMigrationWithFields()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('migration/migration-with-fields.php', $this->generator->render());
    }
}
