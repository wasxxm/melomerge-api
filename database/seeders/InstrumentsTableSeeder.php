<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstrumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instruments = [
            'Electric Guitar',
            'Acoustic Guitar',
            'Bass Guitar',
            'Drum Kit',
            'Piano',
            'Keyboard',
            'Saxophone',
            'Trumpet',
            'Violin',
            'Cello',
            'Flute',
            'Harp',
            'Turntables',
            'Synthesizer',
            'Conga Drums',
            'Bongos',
            'Clarinet',
            'Trombone',
            'Accordion',
            'Mandolin',
            'Ukulele',
            'Banjo'
        ];

        foreach ($instruments as $instrument) {
            Instrument::create(['name' => $instrument]);
        }
    }
}
