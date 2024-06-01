<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['genre_name' => 'Action'],
            ['genre_name' => 'Adventure'],
            ['genre_name' => 'Animation'],
            ['genre_name' => 'Biography'],
            ['genre_name' => 'Comedy'],
            ['genre_name' => 'Crime'],
            ['genre_name' => 'Documentary'],
            ['genre_name' => 'Drama'],
            ['genre_name' => 'Family'],
            ['genre_name' => 'Fantasy'],
            ['genre_name' => 'History'],
            ['genre_name' => 'Horror'],
            ['genre_name' => 'Music'],
            ['genre_name' => 'Musical'],
            ['genre_name' => 'Mystery'],
            ['genre_name' => 'Romance'],
            ['genre_name' => 'Science Fiction'],
            ['genre_name' => 'Sport'],
            ['genre_name' => 'Thriller'],
            ['genre_name' => 'War'],
            ['genre_name' => 'Western'],
        ]);
    }
}
