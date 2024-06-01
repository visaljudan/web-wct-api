<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieLanguageSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('movie_languages')->insert([
            [
                'movie_id' => 1,
                'language_code' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie-language associations as needed
        ]);
    }
}
