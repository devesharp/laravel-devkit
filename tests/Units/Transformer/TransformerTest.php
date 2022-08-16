<?php

namespace Tests\Units\Transformer;

use Carbon\Carbon;
use Devesharp\CRUD\Exception;
use Devesharp\CRUD\Transformer;
use Devesharp\Support\Collection;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class TransformerTest extends \Tests\TestCase
{
    public TransformerStub $transformer;
    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new TransformerStub();
    }

    /**
     * @testdox Transformer - transformar apenas um resultado
     */
    public function testTransformerItem()
    {
        $model = ModelStub::query()->create([
            'name' => 's',
            'age' => 17,
        ]);

        $this->assertEquals(Transformer::item($model, $this->transformer), [
            'name' => 's',
            'age' => 17,
            'user_create' => null,
            'updated_at' => (string) $model->updated_at,
            'created_at' => (string) $model->created_at,
            'id' => 1,
        ]);
    }

    /**
     * @testdox Transformer - transformar vários resultados
     */
    public function testTransformerItems()
    {
        $model = ModelStub::query()->create([
            'name' => 's',
            'age' => 17,
        ]);
        $model2 = ModelStub::query()->create([
            'name' => 'john',
            'age' => 20,
        ]);
        $model3 = ModelStub::query()->create([
            'name' => 'veronica',
            'age' => 48,
        ]);

        $this->assertEquals(Transformer::collection(ModelStub::query()->get()->all(), $this->transformer),
            [
                [
                    'name' => 's',
                    'age' => 17,
                    'user_create' => 0,
                    'updated_at' => (string) $model->updated_at,
                    'created_at' => (string) $model->created_at,
                    'id' => 1,
                ],
                [
                    'name' => 'john',
                    'age' => 20,
                    'user_create' => 0,
                    'updated_at' => (string) $model2->updated_at,
                    'created_at' => (string) $model2->created_at,
                    'id' => 2,
                ],
                [
                    'name' => 'veronica',
                    'age' => 48,
                    'user_create' => 0,
                    'updated_at' => (string) $model3->updated_at,
                    'created_at' => (string) $model3->created_at,
                    'id' => 3,
                ]
            ]);
    }

    /**
     * @testdox Transformer - testar repository cache
     */
    public function testTransformerRepositoryCacheDefaut()
    {
        $this->transformer->loadFoo([1]);
        $this->transformer->loadFoo([1]);
        $this->transformer->loadFoo([1]);

        /**
         * Foi mockado a class RepositoryFooStub->findMany para que toda vez que for chamada
         * incrementar em 1 o login, assim temos certeza que loadFoo não está chamando novamente
         */
        $this->assertEquals('john.0', $this->transformer->getFoo(1)->login);
    }


    /**
     * @testdox Transformer - testar repository cache com load automático com foreign key padrão
     */
    public function testTransformerRepositoryCacheAutomaticDefault()
    {
        \Tests\Units\Transformer\Mocks\RepositoryFooStub::$id = 0;

        $transformer = new \Tests\Units\Transformer\Mocks\TransformerWithLoadCacheStub();

        $model = ModelStub::query()->create([
            'name' => 's',
            'user_create' => 1,
            'age' => 17,
        ]);
        $model2 = ModelStub::query()->create([
            'name' => 'john',
            'user_create' => 2,
            'age' => 20,
        ]);
        $model3 = ModelStub::query()->create([
            'name' => 'veronica',
            'user_create' => 3,
            'age' => 48,
        ]);

        $results = Transformer::collection(ModelStub::query()->get()->all(), $transformer);

        $this->assertEquals(\Illuminate\Support\Arr::pluck($results, 'user_create'), [
            'john.0',
            'john.1',
            'john.2',
        ]);
    }

    /**
     * @testdox Transformer - testar repository cache com load automático  com foreign key customizado
     */
    public function testTransformerRepositoryCacheAutomaticCustom()
    {
        \Tests\Units\Transformer\Mocks\RepositoryFooStub::$id = 0;

        $transformer = new \Tests\Units\Transformer\Mocks\TransformerWithLoadCacheStub();

        $model = ModelStub2::query()->create([
            'name' => 's',
            'user_create' => 1,
            'user_edit' => 4,
            'age' => 17,
        ]);
        $model2 = ModelStub2::query()->create([
            'name' => 'john',
            'user_create' => 2,
            'user_edit' => 5,
            'age' => 20,
        ]);
        $model3 = ModelStub2::query()->create([
            'name' => 'veronica',
            'user_create' => 3,
            'user_edit' => 6,
            'age' => 48,
        ]);

        $results = Transformer::collection(ModelStub2::query()->get()->all(), $transformer, 'custom');

        $this->assertEquals(\Illuminate\Support\Arr::pluck($results, 'user_edit'), [
            'john.0',
            'john.1',
            'john.2',
        ]);
    }

    /**
     * @testdox Transformer - transformar apenas um resultado (custom)
     */
    public function testTransformerItemCustom()
    {
        $model = ModelStub::query()->create([
            'name' => 's',
            'age' => 17,
        ]);

        $this->assertEquals(Transformer::item($model, $this->transformer), [
            'name' => 's',
            'age' => 17,
            'user_create' => null,
            'updated_at' => (string) $model->updated_at,
            'created_at' => (string) $model->created_at,
            'id' => 1,
        ]);
    }

    /**
     * @testdox Transformer - transformar vários resultados (custom)
     */
    public function testTransformerItemsCustom()
    {
        $model = ModelStub::query()->create([
            'name' => 's',
            'age' => 17,
        ]);
        $model2 = ModelStub::query()->create([
            'name' => 'john',
            'age' => 20,
        ]);
        $model3 = ModelStub::query()->create([
            'name' => 'veronica',
            'age' => 48,
        ]);

        $this->assertEquals(Transformer::collection(ModelStub::query()->get()->all(), $this->transformer, 'custom'),
            [
                [
                    'name' => 's',
                    'age' => 17,
                    'custom' => true,
                    'user_create' => 0,
                    'updated_at' => (string) $model->updated_at,
                    'created_at' => (string) $model->created_at,
                    'id' => 1,
                ],
                [
                    'name' => 'john',
                    'age' => 20,
                    'custom' => true,
                    'user_create' => 0,
                    'updated_at' => (string) $model2->updated_at,
                    'created_at' => (string) $model2->created_at,
                    'id' => 2,
                ],
                [
                    'name' => 'veronica',
                    'age' => 48,
                    'custom' => true,
                    'user_create' => 0,
                    'updated_at' => (string) $model3->updated_at,
                    'created_at' => (string) $model3->created_at,
                    'id' => 3,
                ]
            ]);
    }
}
