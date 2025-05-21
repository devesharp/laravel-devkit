<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
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
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Transformers');
    }

    public function testTransformerBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('transformer/transformer-simple.php', $this->generator->render());
    }

    public function testTransformerBaseWithFile()
    {
        $this->generator->setTemplateData(TemplateData::makeByFile(__DIR__ . '/mocks/fields.yml'));

        $this->assertTemplate('transformer/transformer-with-file.php', $this->generator->render());
    }
}
