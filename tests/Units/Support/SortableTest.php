<?php

namespace Tests\Support;

use Devesharp\Support\SortableTrait;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

/**
 * Class ModelSortable.
 * @method static ModelSortable create($body = null)
 */
class ModelSortable extends Model
{
    use SortableTrait;

    protected string $orderColumnName = 'order_column';

    public $table = 'model';

    protected $guarded = [];

    public $timestamps = false;
}

/**
 * Class ModelSortableWithTableMysql.
 * @method static ModelSortableWithTableMysql create($body = null)
 */
class ModelSortableWithTableMysql extends Model
{
    use SortableTrait;

    protected string $orderColumnNameForeignKey = 'model_id';

    protected string $orderColumnName = 'order_column';

    protected string $orderTableName = ModelOrder::class;

    public $table = 'model';

    protected $guarded = [];

    public $timestamps = false;
}

/**
 * Class ModelSortableWithTableMysql.
 * @method static ModelSortableWithTableMysql create($body = null)
 */
class ModelOrder extends Model
{
    public $table = 'model_order';

    protected $guarded = [];

    public $timestamps = false;
}

class SortableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('model')) {
            Schema::drop('model');
        }

        if (Schema::hasTable('model_order')) {
            Schema::drop('model_order');
        }

        Schema::create('model', function (
            \Illuminate\Database\Schema\Blueprint $table
        ) {
            $table->increments('id');
            $table->integer('order_column')->nullable();
        });

        Schema::create('model_order', function (
            \Illuminate\Database\Schema\Blueprint $table
        ) {
            $table->increments('id');
            $table->integer('model_id');
            $table->integer('user_id')->nullable();
            $table->integer('order_column')->default(0);
        });
    }

    /**
     * @testdox moveToStart - colocar no final (com outro tabela)
     */
    public function testSortableStartOtherTable()
    {
        $model1 = ModelSortableWithTableMysql::create()->createSortable();
        $model2 = ModelSortableWithTableMysql::create()->createSortable();
        $model3 = ModelSortableWithTableMysql::create()->createSortable();
        // Não deve ordenar
        $model4 = ModelSortableWithTableMysql::create();
        $model5 = ModelSortableWithTableMysql::create();

        // Organizar
        $model3->moveToStart();
        $model1->moveToStart();
        $model2->moveToStart();

        $model2 = ModelOrder::where('model_id', $model2->id)->first();
        $model1 = ModelOrder::where('model_id', $model1->id)->first();
        $model3 = ModelOrder::where('model_id', $model3->id)->first();
        $model4 = ModelOrder::where('model_id', $model4->id)->first();
        $model5 = ModelOrder::where('model_id', $model5->id)->first();

        $this->assertEquals($model2->order_column, 0);
        $this->assertEquals($model1->order_column, 1);
        $this->assertEquals($model3->order_column, 2);
        $this->assertEquals($model4, null);
        $this->assertEquals($model5, null);
    }

    /**
     * @testdox moveToEnd - colocar no final (com outro tabela)
     */
    public function testSortableEndOtherTable()
    {
        $model1 = ModelSortableWithTableMysql::create()->createSortable();
        $model2 = ModelSortableWithTableMysql::create()->createSortable();
        $model3 = ModelSortableWithTableMysql::create()->createSortable();

        // Organizar
        $model3->moveToEnd();
        $model1->moveToEnd();
        $model2->moveToEnd();

        $model2 = ModelOrder::where('model_id', $model2->id)->first();
        $model1 = ModelOrder::where('model_id', $model1->id)->first();
        $model3 = ModelOrder::where('model_id', $model3->id)->first();

        $this->assertEquals($model3->order_column, 0);
        $this->assertEquals($model1->order_column, 1);
        $this->assertEquals($model2->order_column, 2);
    }

    /**
     * @testdox removeSortable - remover ordem (com outro tabela)
     */
    public function testSortableRemoveOrderOtherTable()
    {
        $model1 = ModelSortableWithTableMysql::create()->createSortable();
        $model2 = ModelSortableWithTableMysql::create()->createSortable();
        $model3 = ModelSortableWithTableMysql::create()->createSortable();
        $model4 = ModelSortableWithTableMysql::create()->createSortable();
        $model5 = ModelSortableWithTableMysql::create()->createSortable();

        // Organizar
        $model1->moveToEnd();
        $model2->moveToEnd();
        $model3->moveToEnd();
        $model4->moveToEnd();
        $model5->moveToEnd();

        // Remover
        $model1->removeSortable();
        $model4->removeSortable();

        $model2 = ModelOrder::where('model_id', $model2->id)->first();
        $model1 = ModelOrder::where('model_id', $model1->id)->first();
        $model3 = ModelOrder::where('model_id', $model3->id)->first();
        $model4 = ModelOrder::where('model_id', $model4->id)->first();
        $model5 = ModelOrder::where('model_id', $model5->id)->first();

        $this->assertEquals($model1, null);
        $this->assertEquals($model2->order_column, 0);
        $this->assertEquals($model3->order_column, 1);
        $this->assertEquals($model4, null);
        $this->assertEquals($model5->order_column, 2);
    }

    /**
     * @testdox organizeSortable - Organizar sortable (com outro tabela)
     */
    public function testSortableOrganizeSortableOtherTable()
    {
        $model1 = ModelSortableWithTableMysql::create()->createSortable();
        ModelOrder::where('model_id', $model1->id)->update([
            'order_column' => 0,
        ]);
        $model2 = ModelSortableWithTableMysql::create()->createSortable();
        ModelOrder::where('model_id', $model2->id)->update([
            'order_column' => 3,
        ]);
        $model3 = ModelSortableWithTableMysql::create()->createSortable();
        ModelOrder::where('model_id', $model3->id)->update([
            'order_column' => 8,
        ]);
        $model4 = ModelSortableWithTableMysql::create()->createSortable();
        ModelOrder::where('model_id', $model4->id)->update([
            'order_column' => 8,
        ]);
        $model5 = ModelSortableWithTableMysql::create()->createSortable();
        ModelOrder::where('model_id', $model5->id)->update([
            'order_column' => 50,
        ]);

        (new ModelSortableWithTableMysql())->organizeSortable();

        $model1 = ModelOrder::where('model_id', $model1->id)->first();
        $model2 = ModelOrder::where('model_id', $model2->id)->first();
        $model3 = ModelOrder::where('model_id', $model3->id)->first();
        $model4 = ModelOrder::where('model_id', $model4->id)->first();
        $model5 = ModelOrder::where('model_id', $model5->id)->first();

        $this->assertEquals($model1->order_column, 0);
        $this->assertEquals($model2->order_column, 1);
        $this->assertEquals($model3->order_column, 2);
        $this->assertEquals($model4->order_column, 3);
        $this->assertEquals($model5->order_column, 4);
    }

    /**
     * @testdox moveToStart - colocar no final
     */
    public function testSortableStart()
    {
        $model1 = ModelSortable::create();
        $model2 = ModelSortable::create();
        $model3 = ModelSortable::create();
        // Não deve ordenar
        $model4 = ModelSortable::create();
        $model5 = ModelSortable::create();

        // Organizar
        $model3->moveToStart();
        $model1->moveToStart();
        $model2->moveToStart();

        $model2 = ModelSortable::find($model2->id);
        $model1 = ModelSortable::find($model1->id);
        $model3 = ModelSortable::find($model3->id);
        $model4 = ModelSortable::find($model4->id);
        $model5 = ModelSortable::find($model5->id);

        $this->assertEquals($model2->order_column, 0);
        $this->assertEquals($model1->order_column, 1);
        $this->assertEquals($model3->order_column, 2);
        $this->assertEquals($model4->order_column, null);
        $this->assertEquals($model5->order_column, null);
    }

    /**
     * @testdox moveToEnd - colocar no final
     */
    public function testSortableEnd()
    {
        $model1 = ModelSortable::create();
        $model2 = ModelSortable::create();
        $model3 = ModelSortable::create();

        // Organizar
        $model3->moveToEnd();
        $model1->moveToEnd();
        $model2->moveToEnd();

        $model2 = ModelSortable::find($model2->id);
        $model1 = ModelSortable::find($model1->id);
        $model3 = ModelSortable::find($model3->id);

        $this->assertEquals($model3->order_column, 0);
        $this->assertEquals($model1->order_column, 1);
        $this->assertEquals($model2->order_column, 2);
    }

    /**
     * @testdox removeSortable - remover ordem
     */
    public function testSortableRemoveOrder()
    {
        $model1 = ModelSortable::create();
        $model2 = ModelSortable::create();
        $model3 = ModelSortable::create();
        $model4 = ModelSortable::create();
        $model5 = ModelSortable::create();

        // Organizar
        $model1->moveToEnd();
        $model2->moveToEnd();
        $model3->moveToEnd();
        $model4->moveToEnd();
        $model5->moveToEnd();

        // Remover
        $model1->removeSortable();
        $model4->removeSortable();

        $model2 = ModelSortable::find($model2->id);
        $model1 = ModelSortable::find($model1->id);
        $model3 = ModelSortable::find($model3->id);
        $model4 = ModelSortable::find($model4->id);
        $model5 = ModelSortable::find($model5->id);

        $this->assertEquals($model1->order_column, null);
        $this->assertEquals($model2->order_column, 0);
        $this->assertEquals($model3->order_column, 1);
        $this->assertEquals($model4->order_column, null);
        $this->assertEquals($model5->order_column, 2);
    }

    /**
     * @testdox organizeSortable - Organizar sortable
     */
    public function testSortableOrganizeSortable()
    {
        $model1 = ModelSortable::create();
        $model1->setAttribute('order_column', 0)->save();
        $model2 = ModelSortable::create();
        $model2->setAttribute('order_column', 3)->save();
        $model3 = ModelSortable::create();
        $model3->setAttribute('order_column', 8)->save();
        $model4 = ModelSortable::create();
        $model4->setAttribute('order_column', 8)->save();
        $model5 = ModelSortable::create();
        $model5->setAttribute('order_column', 50)->save();

        (new ModelSortable())->organizeSortable();

        $model1 = ModelSortable::find($model1->id);
        $model2 = ModelSortable::find($model2->id);
        $model3 = ModelSortable::find($model3->id);
        $model4 = ModelSortable::find($model4->id);
        $model5 = ModelSortable::find($model5->id);

        $this->assertEquals($model1->order_column, 0);
        $this->assertEquals($model2->order_column, 1);
        $this->assertEquals($model3->order_column, 2);
        $this->assertEquals($model4->order_column, 3);
        $this->assertEquals($model5->order_column, 4);
    }
}
