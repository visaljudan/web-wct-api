<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TVShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tv_shows')->insert([
            [
                'tv_show_name' => 'Korea-Drama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tv_show_name' => 'Khmer-Drama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tv_show_name' => 'Thai-Drama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tv_show_name' => 'America-Drama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tv_show_name' => 'China-Drama',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
