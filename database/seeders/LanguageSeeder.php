<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('languages')->insert([
            [
                'language_code' => 'en',
                'language_name' => 'English',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'language_code' => 'ko',
                'language_name' => 'Korean',
                'created_at' => now(),
                'updated_at;' => now(),
            ],
        ]);
    }
}
