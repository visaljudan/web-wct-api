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
                'poster_image' => 'https://wallpapergod.com/images/hd/iron-man-1920X1080-wallpaper-xpxbmcu6l2c44u4y.jpeg',
                'cover_image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSgE6phBcIIijzvuiUUkoNqrkrLkqd_HIUrqZC7FetlQrPLJbP1zHKcMRDM8hNAxIWPLeU&usqp=CAU',
                'popularity' => 95,
                'terms_status' => 'Public',
                'upload_status' => 'Uploaded',
                'last_upload_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tv_show_id' => 1,
                'title' => 'Doctor Stranger',
                'overview' => 'Park Hoon and his father were kidnapped by North Korea and he fell in love with Jae Hee, but lost contact.',
                'run_time' => 115,
                'release_date' => '2016-11-04',
                'poster_image' => 'https://m.media-amazon.com/images/M/MV5BZGVmMWM5MTItMWE1Yi00MmZiLWI4ODUtZTg3OGVhZWI4NDdiL2ltYWdlL2ltYWdlXkEyXkFqcGdeQXVyNjk2NjIzMDI@._V1_.jpg',
                'cover_image' => 'https://i.pinimg.com/originals/c9/0e/17/c90e173c0c54c89d637dc962fad380b3.jpg',
                'popularity' => 90,
                'terms_status' => 'Public',
                'upload_status' => 'Uploaded',
                'last_upload_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tv_show_id' => null,
                'title' => 'Spider-Man: No Way Home',
                'overview' => "With Spider-Man's identity now revealed, Peter asks Doctor Strange for help. When a spell goes wrong, dangerous foes from other worlds start to appear, forcing Peter to discover what it truly means to be Spider-Man.",
                'run_time' => 148,
                'release_date' => '2021-12-13',
                'poster_image' => 'https://m.media-amazon.com/images/M/MV5BZGVmMWM5MTItMWE1Yi00MmZiLWI4ODUtZTg3OGVhZWI4NDdiL2ltYWdlL2ltYWdlXkEyXkFqcGdeQXVyNjk2NjIzMDI@._V1_.jpg',
                'cover_image' => 'https://cdn.wallpapersafari.com/30/28/2GuUqd.jpg',
                'popularity' => 85,
                'terms_status' => 'Public',
                'upload_status' => '',
                'last_upload_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
