<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\JamParticipant;
use App\Models\JamSession;
use App\Models\Role;
use App\Models\SkillLevel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JamParticipant>
 */
class JamParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jam_session_id' => JamSession::inRandomOrder()->first()->id, // Select a random jam session
            'user_id' => User::inRandomOrder()->first()->id, // Select a random user
            'role_id' => Role::inRandomOrder()->first()->id,
            'instrument_id' => Instrument::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence(10),
            'skill_level_id' => SkillLevel::inRandomOrder()->first()->id,
            // Add other fields as necessary
        ];
    }
}
