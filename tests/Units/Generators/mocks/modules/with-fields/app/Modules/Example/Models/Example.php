<?php

namespace App\Modules\Example\Resources\Models;

use App\Modules\Example\Resources\Presenters\ExamplePresenter;
use Devesharp\Patterns\Presenter\PresentableTrait;
use App\Modules\Example\Resources\Factories\ExampleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Example
 * @package App\Modules\Example\Resources\Models
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
 * @method \App\Modules\Example\Resources\Presenters\ExamplePresenter present()
 * @method static App\Modules\Example\Resources\Models\Example find($value)
 */
class Example extends Model
{
    use ModelGetTable;
    use PresentableTrait;
    use HasFactory;

    protected $table = 'example';

    protected string $presenter = ExamplePresenter::class;

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
    protected static function newFactory()
    {
        return ExampleFactory::new();
    }

    public function platform(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Example\Resources\Models\Platforms {
        return $this->belongsTo(\App\Modules\Example\Resources\Models\Platforms::class, 'platform_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Example\Resources\Models\Users {
        return $this->belongsTo(\App\Modules\Example\Resources\Models\Users::class, 'user_id', 'id');
    }

    public function userCreatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Example\Resources\Models\Users {
        return $this->belongsTo(\App\Modules\Example\Resources\Models\Users::class, 'created_by', 'id');
    }

}
