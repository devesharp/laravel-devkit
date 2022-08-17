<?php

namespace Tests\Units\Validators;

use Devesharp\Exceptions\Exception;
use Devesharp\Support\Collection;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use Tests\Units\Validators\Mocks\ValidatorStub;

class ValidatorTest extends \Tests\TestCase
{
    public ValidatorStub $validator;
    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ValidatorStub();
    }

    /**
     * @testdox Validators - validação com erro
     */
    public function testValidatorStubError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(Exception::DATA_ERROR);
        $this->expectExceptionMessage("Error on validate data:\nThe age field is required.");

        $this->validator->create(['name' => 'John']);
    }

    /**
     * @testdox Validators - validação sem erro
     */
    public function testValidatorStub()
    {
        $data = $this->validator->create(['name' => 'John', 'age' => 10, 'extends' => true]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John', 'age' => 10]);
    }

    /**
     * @testdox Validators - removeRequiredRules deve ignorar todos os required
     */
    public function testValidatorStubRemoveRequiredRules()
    {
        // name é required em create
        $data = $this->validator->update(['name' => 'John']);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John']);
    }

    /**
     * @testdox Validators - additionalProperties = false deve ignorar valores que não estiverem no schema
     */
    public function testValidatorStubAdditional()
    {
        $this->validator->setAdditionalProperties(false);

        $data = $this->validator->update(['name' => 'John', 'value' => 'test']);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John']);
    }

    /**
     * @testdox Validators - additionalProperties = true deve deixar valores que não estiverem no schema
     */
    public function testValidatorStubAdditionalTrue()
    {
        $this->validator->setAdditionalProperties(true);

        $data = $this->validator->update(['name' => 'John', 'value' => 'test']);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), ['name' => 'John', 'value' => 'test']);
    }

    /**
     * @testdox Validators - getValidateWithSearch deve ter schema de busca ['query' => []]
     */
    public function testValidatorStubSearch()
    {
        $data = $this->validator->search([
            'query' => [
                'limit' => 1,
                'pagination' => 1,
                'offset' => 1,
                'sort' => '-id',
            ],
            'filters' => [
                'name' => 'John',
                'age' => 17,
            ]
        ]);

        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals($data->toArray(), [
            'query' => [
                'limit' => 1,
                'pagination' => 1,
                'offset' => 1,
                'sort' => '-id',
            ],
            'filters' => [
                'name' => 'John'
            ]
        ]);
    }
}
