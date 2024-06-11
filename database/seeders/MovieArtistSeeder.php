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
                'artist_id' => 1,
                'role_id' => 3,
                'movie_artist_name' => 'Iron Man / Tony Stark',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 1,
                'artist_id' => 2,
                'role_id' => 3,
                'movie_artist_name' => 'Pepper Potts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'artist_id' => 3,
                'role_id' => 3,
                'movie_artist_name' => 'Park Hoon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 2,
                'artist_id' => 4,
                'role_id' => 3,
                'movie_artist_name' => 'Han Seung-Hee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'artist_id' => 5,
                'role_id' => 3,
                'movie_artist_name' => 'Peter Parker / Spider-Man',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'artist_id' => 6, // Another Artist ID
                'role_id' => 4,   // Another Role ID
                'movie_artist_name' => "Doctor Strange",
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'movie_id' => 3,
                'artist_id' => 7, // Another Artist ID
                'role_id' => 3,   // Another Role ID
                'movie_artist_name' => "MJ",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'artist_id' => 8, // Another Artist ID
                'role_id' => 4,   // Another Role ID
                'movie_artist_name' => "Coach Wilson",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'artist_id' => 9, // Another Artist ID
                'role_id' => 1,   // Another Role ID
                'movie_artist_name' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 3,
                'artist_id' => 10, // Another Artist ID
                'role_id' => 5,   // Another Role ID
                'movie_artist_name' => "Happy Hogan",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'movie_id' => 1,
                'artist_id' => 10, // Another Artist ID
                'role_id' => 1,   // Another Role ID
                'movie_artist_name' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
