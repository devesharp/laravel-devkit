<?php

namespace App\Modules\Products\Resources\Models;

use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\Resources\Presenters\EletronicsPresenter;
use Devesharp\Patterns\Presenter\PresentableTrait;

/**
 * Class Eletronics
 * @package App\Modules\Products\Resources\Models
 *
 * @method \App\Modules\Products\Resources\Presenters\EletronicsPresenter present()
 * @method static App\Modules\Products\Resources\Models\Eletronics find($value)
 */
class Eletronics extends Model
{
    use ModelGetTable;
    use PresentableTrait;

    protected $table = 'eletronics';

    protected string $presenter = EletronicsPresenter::class;

    protected $guarded = [];

    protected $casts = [
    ];

}
