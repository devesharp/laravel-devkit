<?php

namespace App\Modules\ModuleExample\Resources\Models;

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

    protected $table = 'resource_example';

    protected $guarded = [];

    protected $casts = [
    ];

}
