<?php

namespace App\Modules\ModuleExample\Resources\Factory;

use App\Modules\ModuleExample\Resources\Model\ResourceExample;
use Devesharp\Support\Factory;

class ResourceExampleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResourceExample::class;

    protected $onlyRaw = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        ];
    }
}
