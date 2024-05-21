<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ],
            [
                'username' => 'User',
                'email' => 'user@user.com',
                'password' => Hash::make('password'),
                'role' => 'User',
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            $user->api_token = $token;
            $user->save();
        }
    }
}
