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
                'role_name' => 'Screenwriter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Actor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Cinematographer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Editor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Composer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Production Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Sound Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Costume Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Makeup Artist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Casting Director',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
