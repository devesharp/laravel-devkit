<?php

namespace Tests\Units\Generators;

use Carbon\Carbon;
use Devesharp\Exceptions\Exception;
use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\ControllerGenerator;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Illuminate\Routing\Controller;
use Tests\Units\Presenter\Mocks\ModelPresenter;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class ControllerTest extends TestCaseGenerator
{
    public ControllerGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(ControllerGenerator::class);
    }

    public function testNamespaceController()
    {
        $data = new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        );
        $this->generator->setTemplateData($data);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Resources\Controllers');
    }

    public function testControllerBase()
    {
        $data = new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        );
        $this->generator->setTemplateData($data);

        $this->assertTemplate('controller/controller-simple.php', $this->generator->render());
    }

    public function testControllerWithTransformerInterface()
    {
        $data = new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
            withTransformerInterface: true
        );
        $this->generator->setTemplateData($data);

        $this->assertTemplate('controller/controller-with-interface-transformer.php', $this->generator->render());
    }
}
