<?php

namespace Tests\Units\Presenter\Mocks;

use Devesharp\Patterns\Presenter\Presenter as PresenterDefault;

class Presenter extends PresenterDefault {

    public function fullName()
    {
        return $this->name . ' Wick';
    }
}
