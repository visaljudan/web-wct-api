<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            [
                'country_code' => 'US',
                'country_name' => 'United States',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'KR',
                'country_name' => 'South Korea',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
