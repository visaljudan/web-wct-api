<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movie_artists')->insert([
            [
                'movie_id' => 1,
                'artist_id' => 1, // Artist ID
                'role_id' => 1,   // Role ID
                'movie_artist_name' => 'Robert Downey Jr.', // Name if different from the artist's actual name
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 1,
                'artist_id' => 2, // Another Artist ID
                'role_id' => 5,   // Another Role ID
                'movie_artist_name' => "Tony Stark's wife", // Corrected the movie artist name
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie artist data as needed
        ]);
    }
}
