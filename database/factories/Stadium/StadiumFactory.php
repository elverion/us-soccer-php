<?php

namespace Database\Factories\Stadium;

use App\Stadium\Stadium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class StadiumFactory extends Factory
{
    protected $model = Stadium::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company,
            'city' => fake()->city,
            'country' => fake()->country,
            'lat' => fake()->latitude,
            'long' => fake()->longitude
        ];
    }
}
