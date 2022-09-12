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
            'enabled' => fake()->boolean(),
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
