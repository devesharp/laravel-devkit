<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\FileSystem;
use Devesharp\Generators\Common\GeneratorConfig;
use Devesharp\Generators\MigrationGenerator;
use Devesharp\Generators\WorkspaceGenerator;
use Devesharp\Generators\TransformerGenerator;
use Illuminate\Support\Facades\Config;

class WorkspaceGeneratorTest extends TestCaseGenerator
{
    public WorkspaceGenerator $generator;
    public FileSystem $fileSystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileSystem = app(FileSystem::class);

        $mock = \Mockery::mock(MigrationGenerator::class, [new GeneratorConfig(),$this->fileSystem])->makePartial();
        $mock->shouldReceive('getFileName')->andReturn('create_service_table.php');
        $this->instance(MigrationGenerator::class, $mock);

        $this->generator = app(WorkspaceGenerator::class);
    }

    /**
     * @testdox Gerar módulo básico com arquivo de campos
     */
    public function testWorkspaceWithFile()
    {
        $this->generator->setData([
            'file_template' => __DIR__ . '/mocks/workspace/workspace.yml'
        ]);

        $dirMock = __DIR__ . '/mocks/modules/with-fields/';
        $prefix = '';
        Config::set('devesharp_dev_kit.path', [
            'controller' => $prefix . 'app/Modules/{{ModuleName}}/Controllers',
            'dto' => $prefix . 'app/Modules/{{ModuleName}}/Dtos',
            'service' => $prefix . 'app/Modules/{{ModuleName}}/Services',
            'factory' => $prefix . 'app/Modules/{{ModuleName}}/Factories',
            'model' => $prefix . 'app/Modules/{{ModuleName}}/Models',
            'policy' => $prefix . 'app/Modules/{{ModuleName}}/Policies',
            'presenter' => $prefix . 'app/Modules/{{ModuleName}}/Presenters',
            'repository' => $prefix . 'app/Modules/{{ModuleName}}/Repositories',
            'routeDocs' => $prefix . 'app/Modules/{{ModuleName}}/Supports/Docs',
            'transformerInterface' => $prefix . 'app/Modules/{{ModuleName}}/Interfaces',
            'transformer' => $prefix . 'app/Modules/{{ModuleName}}/Transformers',
            'migration' => $prefix . 'database/migrations',
            'testRoute' => $prefix . 'tests/Routes/{{ModuleName}}',
            'testUnit' => $prefix . 'tests/Units/{{ModuleName}}',
            'api_routes' => $prefix . 'routes/api.php',
        ]);

        $this->generator->generate();
        var_dump($this->fileSystem->getFiles());


//        $this->fileSystem->render();

//        foreach ($this->fileSystem->three as $filename => $content) {
//            $this->assertEquals(file_get_contents($dirMock . $filename), $content, $filename);
//        }
    }
}
