<?php

namespace Tests\Units\Presenter\Mocks;

use Devesharp\Patterns\Presenter\Presenter as PresenterDefault;

/**
 * @property string $fullName
 */
class Presenter extends PresenterDefault {

    public function fullName()
    {
        return $this->name . ' Wick';
    }
}
