<?php

namespace Database\Factories;

use App\Models\JamType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JamType>
 */
class JamTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(10),
        ];
    }
}
