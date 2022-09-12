<?php

namespace Tests\Units\Generators;

use Devesharp\Generators\PolicyGenerator;

class PolicyGeneratorsTest extends \Tests\TestCase
{

    public PolicyGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(PolicyGenerator::class);
    }

    public function testNamespacePolicy()
    {
        $this->generator->setData([
            'module' => 'ModuleMain',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals($this->generator->getNamespace(), 'App\Modules\ModuleMain\Policy');
    }

    public function testPolicyBase()
    {
        $this->generator->setData([
            'module' => 'ModuleExample',
            'name' => 'ResourceExample',
        ]);

        $this->assertEquals(file_get_contents(__DIR__ . '/mocks/policy/policy-simple.php'), $this->generator->render());
    }

}
