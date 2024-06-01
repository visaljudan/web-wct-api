<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatedMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rated_movies')->insert([
            [
                'user_id' => 2,
                'movie_id' => 1,
                'rated_value' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more rated movie entries as needed
        ]);
    }
}
