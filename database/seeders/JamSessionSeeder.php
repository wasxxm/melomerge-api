<?php
// database/seeders/JamSessionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JamSession;
use App\Models\User;

class JamSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Assuming you have a User model and each jam session belongs to a user
        $users = User::all();

        // Create a jam session for each user
        $users->each(function ($user) {
            JamSession::factory()->create([
                'organizer_id' => $user->id,
                // Other fields can be filled by the factory
            ]);
        });
    }
}

