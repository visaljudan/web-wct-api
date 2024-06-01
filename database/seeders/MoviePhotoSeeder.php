<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviePhotoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movie_photos')->insert([
            [
                'movie_id' => 1,
                'photo_path' => 'https://static1.srcdn.com/wordpress/wp-content/uploads/2023/04/screen-shot-2023-04-02-at-13-10-17.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more movie photo entries as needed
        ]);
    }
}
