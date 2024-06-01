<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieGenreSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('movie_genres')->insert([
            [
                'movie_id' => 1,
                'genre_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie-genre associations as needed
        ]);
    }
}
