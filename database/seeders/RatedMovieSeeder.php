<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatedMovieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rated_movies')->insert([
            [
                'user_id' => 2,
                'movie_id' => 1,
                'rated_value' => 3,
            ],
            [
                'user_id' => 3,
                'movie_id' => 3,
                'rated_value' => 4,
            ],
            [
                'user_id' => 3,
                'movie_id' => 2,
                'rated_value' => 5,
            ],
        ]);
    }
}
