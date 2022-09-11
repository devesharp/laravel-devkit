<?php

namespace Tests\Units\Generators;

use Carbon\Carbon;
use Devesharp\Exceptions\Exception;
use Devesharp\Generators\ControllerGenerator;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Illuminate\Routing\Controller;
use Tests\Units\Presenter\Mocks\ModelPresenter;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class ControllerTest extends \Tests\TestCase
{

    public ControllerGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ControllerGenerator::class);
    }

    public function testNamespaceController()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Controller');
    }

    public function testControllerBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/controller/controller-simple.php'), $this->generator->render());
    }

    public function testControllerWithTransformerInterface()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
            'withTransformerInterface' => true,
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/controller/controller-with-interface-transformer.php'), $this->generator->render());
    }

}
