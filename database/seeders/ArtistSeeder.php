<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artists')->insert([
            [
                'name' => 'Robert Downey Jr.',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BNzg1MTUyNDYxOF5BMl5BanBnXkFtZTgwNTQ4MTE2MjE@._V1_.jpg',
            ],
            [
                'name' => 'Gwyneth Paltrow',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BNzIxOTQ1NTU1OV5BMl5BanBnXkFtZTcwMTQ4MDY0Nw@@._V1_.jpg',
            ],
            [
                'name' => 'Lee Jong-suk',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BMjVmMDdjNWQtYzg0NC00YWRiLWI3ODktNTc3NDk1ZDBjYzU5XkEyXkFqcGdeQXVyMjMxNTAxNDk@._V1_.jpg',
            ],
            [
                'name' => 'Jin Se-yeon',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BMTBkZDZiYjAtZGVjNi00ZGU0LWE5ODYtZTE3MDIyNzBlNzQ1XkEyXkFqcGdeQXVyNTM3MDMyMDQ@._V1_FMjpg_UX1000_.jpg',
            ],
            [
                'name' => 'Tom Holland',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BNzZiNTEyNTItYjNhMS00YjI2LWIwMWQtZmYwYTRlNjMyZTJjXkEyXkFqcGdeQXVyMTExNzQzMDE0._V1_UX32_CR0,0,32,44_AL_.jpg',
            ],
            [
                'name' => 'Benedict Cumberbatch',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BMjE0MDkzMDQwOF5BMl5BanBnXkFtZTgwOTE1Mjg1MzE@._V1_UX32_CR0,0,32,44_AL_.jpg',
            ],
            [
                'name' => 'Zendaya',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BMjAxZTk4NDAtYjI3Mi00OTk3LTg0NDEtNWFlNzE5NDM5MWM1XkEyXkFqcGdeQXVyOTI3MjYwOQ@@._V1_UY44_CR0,0,32,44_AL_.jpg',
            ],
            [
                'name' => 'Hannibal Buress',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BMzMyYTg3YmEtZjJkZC00NjIzLWE1NWItZTNkODg3NDgzOGI1XkEyXkFqcGdeQXVyMTUyMTgzNjY4._V1_UY44_CR13,0,32,44_AL_.jpg',
            ],
            [
                'name' => 'Jon Watts',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BNzg2NjA5ODAyMV5BMl5BanBnXkFtZTgwODAzMjkxNDE@._V1_.jpg',
            ],
            [
                'name' => 'Jon Favreau',
                'profile_image' => 'https://m.media-amazon.com/images/M/MV5BNjcwNzg4MjktNDNlMC00M2U1LWJmMjgtZTVkMmI4MDI2MTVmXkEyXkFqcGdeQXVyMjI4MDI0NTM@._V1_UY44_CR0,0,32,44_AL_.jpg',
            ],

        ]);
    }
}
