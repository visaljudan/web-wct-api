<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artists')->insert([
            [
                'artist_name' => 'Robert Downey Jr.',
                'artist_profile' => 'https://m.media-amazon.com/images/M/MV5BNzg1MTUyNDYxOF5BMl5BanBnXkFtZTgwNTQ4MTE2MjE@._V1_.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'artist_name' => 'Gwyneth Paltrow',
                'artist_profile' => 'https://m.media-amazon.com/images/M/MV5BNzIxOTQ1NTU1OV5BMl5BanBnXkFtZTcwMTQ4MDY0Nw@@._V1_.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'artist_name' => 'Lee Jong-suk',
                'artist_profile' => 'https://m.media-amazon.com/images/M/MV5BMjVmMDdjNWQtYzg0NC00YWRiLWI3ODktNTc3NDk1ZDBjYzU5XkEyXkFqcGdeQXVyMjMxNTAxNDk@._V1_.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'artist_name' => 'Jin Se-yeon',
                'artist_profile' => 'https://m.media-amazon.com/images/M/MV5BMTBkZDZiYjAtZGVjNi00ZGU0LWE5ODYtZTE3MDIyNzBlNzQ1XkEyXkFqcGdeQXVyNTM3MDMyMDQ@._V1_FMjpg_UX1000_.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
