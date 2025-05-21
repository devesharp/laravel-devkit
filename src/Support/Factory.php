<?php

namespace Devesharp\Support;

use Illuminate\Database\Eloquent\Factories\Factory as FactoryLaravel;
use Illuminate\Database\Eloquent\Model;

class Factory extends FactoryLaravel
{

    protected $onlyRaw = [];

    public function definition()
    {
        return [];
    }

    protected function makeInstance(?Model $parent)
    {
        return Model::unguarded(function () use ($parent) {
            return tap($this->newModel($this->getExpandedAttributes($parent, false)), function ($instance) {
                if (isset($this->connection)) {
                    $instance->setConnection($this->connection);
                }
            });
        });
    }

    /**
     * Get a raw attributes array for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $parent
     * @return mixed
     */
    protected function getExpandedAttributes(?Model $parent, $onlyRaw = true)
    {
        return $this->expandAttributes(Helpers::arrayExclude($this->getRawAttributes($parent), !$onlyRaw ? $this->onlyRaw: []));
    }
}
