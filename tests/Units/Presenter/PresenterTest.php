<?php

namespace Tests\Units\Presenter;

use Carbon\Carbon;
use Devesharp\Exceptions\Exception;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Tests\Units\Presenter\Mocks\ModelPresenter;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class PresenterTest extends \Tests\TestCase
{
    public ModelPresenter $model;

    protected function setUp(): void
    {
        parent::setUp();

        ModelStub::query()->create([
            'name' => 'John',
            'user_create' => 1,
            'age' => 17,
        ]);

        $this->model = ModelPresenter::find(1);
    }

    /**
     * @testdox Presenter - resgatar valor de presenter de função
     */
    public function testTransformerItem()
    {
        $this->assertEquals($this->model->present()->fullName, 'John Wick');
    }

    /**
     * @testdox Presenter - resgatar valor de presenter não definido
     */
    public function testTransformerItemError()
    {
        $this->assertEquals($this->model->present()->wrongValue, null);
    }
}
