<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\PolicyGenerator;

class PolicyGeneratorsTest extends TestCaseGenerator
{

    public PolicyGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(PolicyGenerator::class);
    }

    public function testNamespacePolicy()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\Products\Policies');
    }

    public function testPolicyBase()
    {
        $this->generator->setTemplateData(new TemplateData(
            moduleName: 'Products',
            resourceName: 'Eletronics',
        ));

        $this->assertTemplate('policy/policy-simple.php', $this->generator->render());
    }

}
