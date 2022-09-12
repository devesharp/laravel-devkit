<?php

namespace App\Modules\ModuleExample\Resources\Model;

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

    protected $table = 'resource_example';

    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'cast',
        'enabled' => 'bool',
        'is_featured' => 'bool',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];
}