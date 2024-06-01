<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('movies')->insert([
            [
                'tv_show_id' => null,
                'title' => 'Iron Man',
                'overview' => 'After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.',
                'run_time' => 126,
                'release_date' => '2008-05-02',
                'poster_image' => 'iron_man_poster.jpg',
                'cover_image' => 'iron_man_cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=8hYlB38asDY',
                'total_likes' => 15000,
                'total_ratings' => 2000,
                'average_rating' => 8.5,
                'popularity' => 95,
                'terms_status' => 'Public',
                'upload_status' => 'Uploaded',
                'user_subscription' => false,
                'expire_subscription' => null,
                'last_upload_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tv_show_id' => 1,
                'title' => 'Doctor Strange',
                'overview' => 'While on a journey of physical and spiritual healing, a brilliant neurosurgeon is drawn into the world of the mystic arts.',
                'run_time' => 115,
                'release_date' => '2016-11-04',
                'poster_image' => 'doctor_strange_poster.jpg',
                'cover_image' => 'doctor_strange_cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=HSzx-zryEgM',
                'total_likes' => 12000,
                'total_ratings' => 1800,
                'average_rating' => 7.9,
                'popularity' => 90,
                'terms_status' => 'Public',
                'upload_status' => 'Uploaded',
                'user_subscription' => false,
                'expire_subscription' => null,
                'last_upload_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
