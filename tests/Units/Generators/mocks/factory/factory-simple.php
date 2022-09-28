<?php

namespace App\Modules\Products\Resources\Factories;

use Devesharp\Support\Factory;
use App\Modules\Products\Resources\Models\Eletronics;

class EletronicsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Eletronics::class;

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

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function bodyForRequest(): array
    {
        return [
        ];
    }
}
