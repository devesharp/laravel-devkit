<?php

namespace App\Modules\Example\Resources\Factories;

use App\Modules\Example\Resources\Models\Example;
use Devesharp\Support\Factory;

class ExampleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Example::class;

    protected $onlyRaw = [];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'enabled' => true,
            'platform_id' => 1,
            'user_id' => 1,
            'title' => fake()->text(100),
            'body' => fake()->text(100),
            'is_featured' => fake()->boolean(),
            'published_at' => fake()->date('Y-m-d'),
            'password' => fake()->text(100),
            'post_type' => fake()->randomFloat(2),
            'status' => fake()->randomFloat(2),
            'created_by' => 1,
        ];
    }
}
