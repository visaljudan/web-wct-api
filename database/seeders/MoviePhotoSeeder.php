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
                'photo_image' => 'https://static1.srcdn.com/wordpress/wp-content/uploads/2023/04/screen-shot-2023-04-02-at-13-10-17.jpg',

            ],
            [
                'movie_id' => 2,
                'photo_image' => 'https://i.pinimg.com/originals/c9/0e/17/c90e173c0c54c89d637dc962fad380b3.jpg',
            ],
            [
                'movie_id' => 2,
                'photo_image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRc0zIWVO3GA5A6ZViGwO0S-bXJeX8sO6oSaA&s',
            ],
            [
                'movie_id' => 3,
                'photo_image' => 'https://hips.hearstapps.com/hmg-prod/images/spider-man-no-way-home-zendaya-tom-holland-1638446170.jpg?crop=0.644xw:0.480xh;0.146xw,0.168xh&resize=1200:*',
            ],
            [
                'movie_id' => 3,
                'photo_image' => 'https://hips.hearstapps.com/hmg-prod/images/spider-man-no-way-home-credits-scene-1639668077.jpeg',
            ],
            [
                'movie_id' => 3,
                'photo_image' => 'https://static1.srcdn.com/wordpress/wp-content/uploads/2021/12/Spider-Man-No-Way-Home-Post-Credits-Scenes-Explained.jpg',
            ],
        ]);
    }
}
