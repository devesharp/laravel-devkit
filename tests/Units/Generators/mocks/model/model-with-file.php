<?php

namespace App\Modules\ModuleExample\Resources\Model;

use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceExample
 * @package App\Modules\ModuleExample\Resources\Model
 *
 * @property bool $enabled
 * @property string $platform_id
 * @property string $user_id
 * @property string $title
 * @property string $body
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon $published_at
 * @property string $password
 * @property integer $post_type
 * @property integer $status
 * @property string $created_by
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

    public function platform(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\ModuleExample\Resources\Model\Platforms {
        return $this->belongsTo(\App\Modules\ModuleExample\Resources\Model\Platforms::class, 'platform_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\ModuleExample\Resources\Model\Users {
        return $this->belongsTo(\App\Modules\ModuleExample\Resources\Model\Users::class, 'user_id', 'id');
    }

    public function userCreatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\ModuleExample\Resources\Model\Users {
        return $this->belongsTo(\App\Modules\ModuleExample\Resources\Model\Users::class, 'created_by', 'id');
    }

}
