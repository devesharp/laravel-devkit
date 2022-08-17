<?php

namespace Tests\Units\Validators;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Tests\Units\Validators\Mocks\ValidatorStub;
use Tests\Units\Validators\Mocks\ValidatorStubWithGenerator;

class ValidatorGeneratorTest extends \Tests\TestCase
{
    public ValidatorStubWithGenerator $validator;
    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ValidatorStubWithGenerator();
    }

    /**
     * @testdox getDataModel - resgata valores do schema
     */
    public function testValidatorStubError()
    {
        $b = $this->validator->getDataModel('complex', ['name' => 'John'], true);

        $this->assertEquals([
                "name" => "John",
                "age" => "string",
                "active" => "string",
                "pets" => [
                    [
                        "id" => "string",
                        "name" => "string"
                    ]
                ],
                "owner" => [
                    "id" => "string",
                    "name" => "string",
                    "age" => "string"
                ],
                "item_array_deep" => [
                    [
                        "id" => "string",
                        "name" => "string",
                        "items" => [
                            [
                                "id" => "string",
                                "name" => "string"
                            ]
                        ]
                    ]
                ]
            ], $b);
//        var_dump($b);
    }

    /**
     * @testdox requireds - resgata valores do obrigatórios
     */
    public function testValidatorGetRequireds()
    {
        $requireds = $this->validator->getRequireds('complex');

        $this->assertEquals([
            'pets.0',
            'pets.0.id',
            'pets.0.name',
            'owner',
            'item_array_deep',
            'item_array_deep.0.items',
        ], $requireds);
    }

    /**
     * @testdox description - resgata valores do obrigatórios
     */
    public function testValidatorGetDescription()
    {
        $requireds = $this->validator->getDescriptions('complex');

        $this->assertEquals([
            'name' => 'Nome',
            'age' => 'Idade',
            'active' => 'Ativo',
            'pets.*' => 'ID',
            'pets.*.id' => 'ID',
            'pets.*.name' => 'Nome do Pet',
            'owner' => 'ID do Dono',
            'owner.id' => 'ID do Dono',
            'owner.name' => 'Nome do Dono',
            'owner.age' => 'Idade do Dono',
        ], $requireds);
    }
}
