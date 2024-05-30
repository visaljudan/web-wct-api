<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('movies')->insert([
            'tv_show_id' => null, // Assuming it's not related to any TV show
            'title' => 'Iron Man',
            'overview' => 'After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.',
            'run_time' => 126,
            'release_date' => '2008-05-02',
            'poster_image' => 'iron_man_poster.jpg',
            'cover_image' => 'iron_man_cover.jpg',
            'trailer_url' => 'https://www.youtube.com/watch?v=8ugaeA-nMTc',
            'total_likes' => 0,
            'total_ratings' => 0,
            'average_rating' => null,
            'popularity' => 50,
            'upload_status' => 'completed',
            'last_upload_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
