<?php

namespace App\Modules\Products\Resources\Models;

use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Eletronics
 * @package App\Modules\Products\Resources\Models
 *
 * @method static App\Modules\Products\Resources\Models\Eletronics find($value)
 */
class Eletronics extends Model
{
    use ModelGetTable;

    protected $table = 'eletronics';

    protected $guarded = [];

    protected $casts = [
    ];

}
