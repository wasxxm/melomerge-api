<?php
// database/seeders/MusicPreferenceSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MusicPreference;
use App\Models\User;

class MusicPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        // Assuming you have a User model and music preferences are related to users
        $users = User::all();

        // Create a music preference for each user
        $users->each(function ($user) {
            MusicPreference::factory()->create([
                'user_id' => $user->id,
                // Other fields can be filled by the factory
            ]);
        });
    }
}

