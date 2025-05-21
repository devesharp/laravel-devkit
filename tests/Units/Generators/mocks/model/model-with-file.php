<?php

namespace App\Modules\Products\Resources\Models;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\Resources\Presenters\EletronicsPresenter;
use Devesharp\Patterns\Presenter\PresentableTrait;
use App\Modules\Products\Resources\Factories\EletronicsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Eletronics
 * @package App\Modules\Products\Resources\Models
 *
 * @property bool $enabled
 * @property string $platform_id
 * @property string $user_id
 * @property string $category_id
 * @property string $title
 * @property string $body
 * @property bool $is_featured
 * @property Carbon $published_at
 * @property string $password
 * @property integer $post_type
 * @property integer $status
 * @property string $created_by
 * @method \App\Modules\Products\Resources\Presenters\EletronicsPresenter present()
 * @method static App\Modules\Products\Resources\Models\Eletronics find($value)
 */
class Eletronics extends Model
{
    use ModelGetTable;
    use PresentableTrait;
    use HasFactory;

    protected $table = 'eletronics';

    protected string $presenter = EletronicsPresenter::class;

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
        return EletronicsFactory::new();
    }

    public function platform(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Products\Resources\Models\Platforms {
        return $this->belongsTo(\App\Modules\Products\Resources\Models\Platforms::class, 'platform_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Products\Resources\Models\Users {
        return $this->belongsTo(\App\Modules\Products\Resources\Models\Users::class, 'user_id', 'id');
    }

    public function cartegoryCategoryId(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Products\Resources\Models\Cartegories {
        return $this->belongsTo(\App\Modules\Products\Resources\Models\Cartegories::class, 'category_id', 'id');
    }

    public function userCreatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Modules\Products\Resources\Models\Users {
        return $this->belongsTo(\App\Modules\Products\Resources\Models\Users::class, 'created_by', 'id');
    }

}
