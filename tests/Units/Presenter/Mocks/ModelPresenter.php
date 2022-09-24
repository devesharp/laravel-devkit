<?php

namespace Tests\Units\Presenter\Mocks;

use Devesharp\Patterns\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Presenter present()
 */
class ModelPresenter extends Model
{
    use PresentableTrait;

    protected $table = 'model_stubs';

    protected $presenter = Presenter::class;
}
