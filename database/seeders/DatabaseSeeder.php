<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\JamParticipant;
use App\Models\JamSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            InstrumentsTableSeeder::class,
            GenresTableSeeder::class,
        ]);

        // Create 100 users
        User::factory(100)->create()->each(function ($user, $key) {
            // First 30 users create a jam session each
            if ($key < 30) {
                JamSession::factory()->create(['organizer_id' => $user->id]);
            }
        });

        // Make 30 users participants of the jam sessions
        $jamSessions = JamSession::all();
        $userIds = User::inRandomOrder()->take(80)->pluck('id');

        foreach ($jamSessions as $jamSession) {
            // Shuffle user IDs and take a random number of users to be participants
            $participantsIds = $userIds->shuffle()->take(rand(1, $userIds->count()));

            foreach ($participantsIds as $userId) {
                // Check if the user is already a participant in this jam session
                $exists = JamParticipant::where('jam_session_id', $jamSession->id)
                    ->where('user_id', $userId)->exists();

                if (!$exists) {
                    JamParticipant::factory()->create([
                        'jam_session_id' => $jamSession->id,
                        'user_id' => $userId,
                    ]);
                }
            }
        }
    }
}
