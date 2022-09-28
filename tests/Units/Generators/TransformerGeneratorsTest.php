<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\TransformerGenerator;
use Illuminate\Support\Facades\Config;

class TransformerGeneratorsTest extends TestCaseGenerator
{
    public TransformerGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(TransformerGenerator::class);
    }

    public function testNamespaceTransformer()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Transformers');
    }

    public function testTransformerBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample'
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/transformer/transformer-simple.php'), $this->generator->render());
    }

    public function testTransformerBaseWithFile()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'file_template' => __DIR__ . '/mocks/fields.yml',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/transformer/transformer-with-file.php'), $this->generator->render());
    }
}
