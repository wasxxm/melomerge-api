<?php

namespace Database\Seeders;

use App\Models\SkillLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SkillLevel::factory()->createMany([
            ['name' => 'Novice', 'description' => 'Ideal for those just starting their musical journey, Novices might have basic knowledge of their instrument or vocals but limited experience in playing music with others. This level is perfect for learning the fundamentals and enjoying being part of a musical ensemble.'],
            ['name' => 'Beginner', 'description' => 'Suitable for participants with a grasp of basic musical concepts who can play simple songs or pieces. Beginners are building confidence and foundational techniques in a supportive environment.'],
            ['name' => 'Intermediate', 'description' => 'Aimed at musicians with a good understanding of their instrument or vocals who can comfortably play a variety of songs or pieces. Intermediates are refining skills, learning more complex pieces, and exploring improvisation.'],
            ['name' => 'Advanced', 'description' => 'For those with high proficiency, able to tackle challenging compositions. Advanced musicians have extensive experience and seek to push their abilities further, explore intricate styles, and engage in sophisticated sessions.'],
            ['name' => 'Professional', 'description' => 'Tailored for seasoned musicians who have mastered their craft, often with formal training and significant performance experience. Professionals seek high-level collaborations, networking, and sessions that challenge their skills and creativity in a professional setting.'],
        ]);

    }
}
