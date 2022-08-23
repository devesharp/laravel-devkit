<?php

namespace Tests\Units\Dto;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Tests\Units\Dto\Mocks\AcceptAdditionalValuesDtoStub;
use Tests\Units\Dto\Mocks\CreateDtoStub;
use Tests\Units\Dto\Mocks\HiddenDtoStub;
use Tests\Units\Dto\Mocks\RemoveRequiredsDtoStub;
use Tests\Units\Dto\Mocks\SearchDtoStub;
use Tests\Units\Dto\Mocks\UpdateDtoStub;

class DtoTest extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @testdox Dto - validação com erro
     */
    public function testDtoStubError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(Exception::DATA_ERROR);
        $this->expectExceptionMessage("Error on validate data:\nThe age field is required.");

        new CreateDtoStub(['name' => 'John']);
    }

    /**
     * @testdox Dto - validação sem erro, removendo valores que não estão no validator
     */
    public function testDtoStub()
    {
        $data = new CreateDtoStub(['name' => 'John', 'age' => 10, 'extends' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John', 'age' => 10]);
    }

    /**
     * @testdox Dto - extender outro Dto
     */
    public function testDtoExtend()
    {
        $data = new UpdateDtoStub(['name' => 'John', 'age' => 10, 'extends' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John', 'age' => 10]);
    }


    /**
     * @testdox Dto - extender outro Dto, e remover algum item
     */
    public function testDtoExtendRemoveValue()
    {
        $data = new CreateDtoStub(['name' => 'John', 'age' => 10, 'active' => true]);
        $dataUpdated = new UpdateDtoStub(['name' => 'John', 'age' => 10, 'active' => true]);

        $this->assertEquals(['name' => 'John', 'age' => 10, 'active' => true], $data->toArray());
        $this->assertEquals(['name' => 'John', 'age' => 10], $dataUpdated->toArray());
    }

    /**
     * @testdox Dto - removeRequiredRules deve ignorar todos os required
     */
    public function testDtoStubRemoveRequiredRules()
    {
        // age é required em create, porém não deve ser required aqui
        $data = new RemoveRequiredsDtoStub(['name' => 'John']);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals(['name' => 'John'], $data->toArray());
    }

    /**
     * @testdox Dto - additionalProperties = true deve aceitar qualquer valor em $data
     */
    public function testDtoStubAdditional()
    {
        $data = new AcceptAdditionalValuesDtoStub(['name' => 'John', 'deleted' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals(['name' => 'John', 'deleted' => true], $data->toArray());
    }

    /**
     * @testdox Dto - Validação hidden deve salvar valor, porém não deve exibido em toArray
     */
    public function testDtoStubAdditionalTrue()
    {
        $data = new HiddenDtoStub(['name' => 'John', 'send_email' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals(['name' => 'John'], $data->toArray());
        $this->assertEquals(['name' => 'John', 'send_email' => true], $data->all());
        $this->assertEquals(true, $data['send_email']);
    }

    /**
     * @testdox Dto - getValidateWithSearch deve ter schema de busca ['query' => []]
     */
    public function testDtoStubSearch()
    {
        $data = new SearchDtoStub([
            'query' => [
                'limit' => 10,
                'offset' => 5,
                'sort' => ['-id'],
            ],
            'filters' => [
                'name' => 'John',
                'age' => 17,
            ]
        ]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), [
            'query' => [
                'limit' => 10,
                'offset' => 5,
                'sort' => ['-id'],
            ],
            'filters' => [
                'name' => 'John'
            ]
        ]);
    }
}
