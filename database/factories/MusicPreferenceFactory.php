<?php

namespace Database\Factories;

use App\Models\MusicPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MusicPreference>
 */
class MusicPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // This will create a User for each MusicPreference
            'genre' => $this->faker->word,
            'instrument' => $this->faker->word,
            // Add other fields as necessary
        ];
    }
}
