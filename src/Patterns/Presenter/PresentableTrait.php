<?php

namespace Devesharp\Patterns\Presenter;

use Devesharp\Patterns\Presenter\Exceptions\ServiceException;

trait PresentableTrait
{
    /**
     * @var \Laracodes\Presenter\Presenter
     */
    protected $presenterInstance;

    /**
     * @return mixed
     * @throws ServiceException
     */
    public function present()
    {
        if (is_object($this->presenterInstance)) {
            return $this->presenterInstance;
        }

        if (property_exists($this, 'presenter') and class_exists($this->presenter)) {
            return $this->presenterInstance = new $this->presenter($this);
        }

        throw new ServiceException('Property $presenter was not set correctly in ' . get_class($this));
    }
}
