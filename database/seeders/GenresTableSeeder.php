<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Rock',
            'Jazz',
            'Blues',
            'Hip Hop',
            'Classical',
            'Electronic',
            'Pop',
            'Metal',
            'Country',
            'Folk',
            'Reggae',
            'Punk',
            'R&B',
            'Soul',
            'Funk',
            'Disco',
            'House',
            'Techno',
            'Trance',
            'Ambient',
            'Indie',
            'Gospel',
            'Opera',
            'Ska',
            'Grime',
            'Dubstep',
            'Drum and Bass',
            'World Music',
            'Latin',
            'Afrobeat'
        ];

        foreach ($genres as $genre) {
            Genre::create(['name' => $genre]);
        }
    }
}
