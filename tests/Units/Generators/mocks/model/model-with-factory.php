<?php

namespace App\Modules\Products\Resources\Models;

use Devesharp\Support\ModelGetTable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Products\Resources\Factories\EletronicsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Eletronics
 * @package App\Modules\Products\Resources\Models
 *
 * @method static App\Modules\Products\Resources\Models\Eletronics find($value)
 */
class Eletronics extends Model
{
    use ModelGetTable;
    use HasFactory;

    protected $table = 'eletronics';

    protected $guarded = [];

    protected $casts = [
    ];


    protected static function newFactory()
    {
        return EletronicsFactory::new();
    }
}
