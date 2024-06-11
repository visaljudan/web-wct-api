<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'Director',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Producer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Main Actor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Second Actor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Actor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Extra Actor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
