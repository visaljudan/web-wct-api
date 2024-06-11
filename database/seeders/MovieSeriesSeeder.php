<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeriesSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('movie_series')->insert([
            [
                'movie_id' => 2,
                'season_number' => 1,
                'episode_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'season_number' => 1,
                'episode_number' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'season_number' => 1,
                'episode_number' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie series entries as needed
        ]);
    }
}
