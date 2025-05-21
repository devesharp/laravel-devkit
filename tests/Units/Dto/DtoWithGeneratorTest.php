<?php

namespace Tests\Units\Dto;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Tests\Units\Dto\Mocks\AcceptAdditionalValuesDtoStub;
use Tests\Units\Dto\Mocks\ComplexDtoStub;
use Tests\Units\Dto\Mocks\CreateDtoStub;
use Tests\Units\Dto\Mocks\HiddenDtoStub;
use Tests\Units\Dto\Mocks\RemoveRequiredsDtoStub;
use Tests\Units\Dto\Mocks\SearchDtoStub;
use Tests\Units\Dto\Mocks\UpdateDtoStub;

class DtoWithGeneratorTest extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @testdox getDataModel - resgata valores do schema
     */
    public function testValidatorStubError()
    {
        $b = (new ComplexDtoStub(['name' => 'John'], false))->getDataModel(true);

        $this->assertEquals([
            "name" => "John",
            "age" => 1,
            "active" => false,
            "pets" => [
                [
                    "id" => 1,
                    "name" => "string"
                ]
            ],
            "owner" => [
                "id" => 1,
                "name" => "string",
                "age" => "string"
            ],
            "item_array_deep" => [
                [
                    "id" => 1,
                    "name" => "string",
                    "items" => [
                        [
                            "id" => 1,
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
        $requireds = (new ComplexDtoStub([], false))->getRequireds();

        $this->assertEquals([
            'name',
            'age',
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
        $requireds = (new ComplexDtoStub([], false))->getDescriptions();

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
