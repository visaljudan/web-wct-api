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
            [
                'movie_id' => 1,
                'genre_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 1,
                'genre_id' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'genre_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'genre_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'genre_id' => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'genre_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'genre_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'genre_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Add more movie-genre associations as needed
        ]);
    }
}
