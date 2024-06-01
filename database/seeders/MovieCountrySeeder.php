<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movie_countries')->insert([
            [
                'movie_id' => 1,
                'country_code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie-country associations as needed
        ]);
    }
}
