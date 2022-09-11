<?php

namespace App\Modules\ModuleExample\Resources\Model;

use App\Modules\ModuleExample\Resources\Presenter\ResourceExamplePresenter;
use Devesharp\Patterns\Presenter\PresentableTrait;
use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceExample
 * @package App\Modules\ModuleExample\Resources\Model\
 *

 * @method static App\Modules\ModuleExample\Resources\Model\ResourceExample find($value)
 */
class ResourceExample extends Model
{
    use ModelGetTable;
    use PresentableTrait;

    protected $table = 'resource_example';

    protected string $presenter = ResourceExamplePresenter::class;

    protected $guarded = [];

    protected $casts = [
    ];

}
