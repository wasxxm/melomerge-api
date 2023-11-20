<?php

namespace Database\Seeders;

use App\Models\JamType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JamType::factory()->createMany([
            ['name' => 'Open Jam', 'description' => 'An inclusive and vibrant Open Jam session where musicians of all skill levels, genres, and backgrounds are welcome. This session provides a collaborative space for artists to connect, share, and create music spontaneously. It\'s the perfect environment for improvisation, networking, and experiencing the joy of music in its most free-form expression. Whether you\'re a seasoned musician or just starting, Open Jam is the place to explore your musicality in a supportive and dynamic setting.'],
            ['name' => 'Private Jam', 'description' => 'Private Jam offers an exclusive, intimate setting for musicians who seek a more focused and controlled environment for their musical explorations. Ideal for groups or individuals looking to rehearse, experiment, or develop new material in a secluded space, this session emphasizes privacy and comfort. Tailored for those who prefer a closed setting away from public audiences, Private Jam is the perfect choice for detailed practice sessions, pre-performance preparations, or private gatherings among fellow musicians.'],
            ['name' => 'Professional Jam','description' => 'Professional Jam is designed for the seasoned musician seeking a high-caliber collaborative experience. This session brings together experienced artists from various genres to engage in sophisticated musical dialogues and high-level improvisations. Ideal for professional musicians, session players, and serious enthusiasts, Professional Jam focuses on advanced techniques, complex compositions, and networking with industry peers. It’s an opportunity to challenge your skills, gain new insights, and collaborate with other professionals in a stimulating and ambitious environment.'],
        ]);
    }
}
