<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_user')->insert([
            [
                'user_id' => 2,
                'subscription_plan_id' => 1,
                'subscription_start_date' => now(),
                'subscription_end_date' => now()->addMonths(1),
                'subscription_status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more subscription user entries as needed
        ]);
    }
}
