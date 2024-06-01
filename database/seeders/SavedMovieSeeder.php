<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavedMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('saved_movies')->insert([
            [
                'user_id' => 2,
                'movie_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more saved movie entries as needed
        ]);
    }
}
