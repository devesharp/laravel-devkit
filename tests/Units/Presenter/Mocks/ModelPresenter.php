<?php

namespace Tests\Units\Presenter\Mocks;

use Devesharp\Patterns\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;

class ModelPresenter extends Model
{
    use PresentableTrait;

    protected $table = 'model_stubs';

    protected $presenter = Presenter::class;
}
