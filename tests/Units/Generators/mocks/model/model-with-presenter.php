<?php

namespace App\Modules\ModuleExample\Resources\Models;

use App\Modules\ModuleExample\Resources\Presenters\ResourceExamplePresenter;
use Devesharp\Patterns\Presenter\PresentableTrait;
use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceExample
 * @package App\Modules\ModuleExample\Resources\Models
 *
 * @method static App\Modules\ModuleExample\Resources\Models\ResourceExample find($value)
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
