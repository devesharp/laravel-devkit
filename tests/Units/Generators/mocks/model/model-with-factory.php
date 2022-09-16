<?php

namespace App\Modules\ModuleExample\Resources\Models;

use App\Modules\ModuleExample\Resources\Factories\ResourceExampleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

    protected $table = 'resource_example';

    protected $guarded = [];

    protected $casts = [
    ];

    protected static function newFactory()
    {
        return ResourceExampleFactory::new();
    }
}
