<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\PresenterGenerator;

class PresenterGeneratorsTest extends \Tests\TestCase
{

    public PresenterGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(PresenterGenerator::class);
    }

    public function testNamespacePressenter()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Resources\Presenters');
    }

    public function testPressenterBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/presenter/presenter-simple.php'), $this->generator->render());
    }

}
